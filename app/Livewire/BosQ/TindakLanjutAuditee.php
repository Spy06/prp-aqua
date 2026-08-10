<?php

namespace App\Livewire\BosQ;

use App\Jobs\SendWhatsApp;
use App\Models\BosqTemuan;
use App\Models\BosqTindakLanjut;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class TindakLanjutAuditee extends Component
{
    use WithFileUploads;

    public int $bosqTemuanId;

    public string  $action        = '';
    public ?string $due_date      = null;
    public         $foto_bukti    = []; // array upload
    public string  $status        = 'open';

    public ?string $foto_bukti_path = null;

    #[Locked]
    public string $currentStatus = 'open';

    protected array $allowedStatuses = ['open', 'in_progress', 'closed_pending_qa'];

    protected array $statusOrder = [
        'open'              => 0,
        'in_progress'       => 1,
        'closed_pending_qa' => 2,
        'closed_acc'        => 3, // hanya QA
    ];

    public function mount(int $bosqTemuanId): void
    {
        $this->bosqTemuanId = $bosqTemuanId;
        $this->loadTindakLanjut();
    }

    protected function authorizeAuditee(): bool
    {
        $temuan = BosqTemuan::findOrFail($this->bosqTemuanId);
        if (auth()->id() !== $temuan->auditee_id || auth()->user()->role === 'qa') {
            session()->flash('status_error', 'Akses ditolak: hanya Auditee yang dapat mengubah status ini.');
            return false;
        }
        return true;
    }

    protected function loadTindakLanjut(): void
    {
        $tl = BosqTindakLanjut::where('bosq_temuan_id', $this->bosqTemuanId)->first();

        if ($tl) {
            $this->action          = $tl->action ?? '';
            $this->due_date        = $tl->due_date?->format('Y-m-d');
            $this->foto_bukti_path = $tl->foto_bukti_path;
            $this->status          = $tl->status;
            $this->currentStatus   = $tl->status;
        }
    }

    public function getBuktiPaths(): array
    {
        if (empty($this->foto_bukti_path)) return [];
        $decoded = json_decode($this->foto_bukti_path, true);
        if (is_array($decoded)) return array_values($decoded);
        return [$this->foto_bukti_path];
    }

    public function simpanDetail(): void
    {
        if (!$this->authorizeAuditee()) return;

        $this->validate([
            'action'   => 'required|string|max:2000',
            'due_date' => 'required|date',
        ], [
            'action.required'   => 'Deskripsi tindakan wajib diisi.',
            'due_date.required' => 'Due date wajib diisi.',
        ]);

        BosqTindakLanjut::where('bosq_temuan_id', $this->bosqTemuanId)->update([
            'action'   => $this->action,
            'due_date' => $this->due_date,
        ]);

        session()->flash('detail_success', 'Detail tindak lanjut berhasil disimpan.');
        $this->dispatch('tindakLanjutUpdated');
    }

    public function updatedFotoBukti(): void
    {
        if (!$this->authorizeAuditee()) return;
        $this->uploadFoto();
    }

    public function uploadFoto(): void
    {
        if (!$this->authorizeAuditee()) return;

        $files = is_array($this->foto_bukti) ? $this->foto_bukti : [$this->foto_bukti];
        $files = array_filter($files);

        if (empty($files)) {
            $this->addError('foto_bukti', 'Pilih file bukti terlebih dahulu.');
            return;
        }

        $existing = $this->getBuktiPaths();
        if (count($existing) + count($files) > 3) {
            $this->addError('foto_bukti', 'Maksimal 3 file bukti yang dapat dikumpulkan.');
            $this->foto_bukti = [];
            return;
        }

        try {
            $this->validate([
                'foto_bukti.*' => 'required|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx|max:3072',
            ], [
                'foto_bukti.*.mimes'    => 'Format file harus gambar (JPG, PNG, WEBP) atau dokumen (PDF, DOC, DOCX).',
                'foto_bukti.*.max'      => 'Ukuran setiap file maksimal 3MB.',
                'foto_bukti.*.uploaded' => 'Gagal mengunggah file. Pastikan ukuran file maksimal 3MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->foto_bukti = [];
            throw $e;
        }

        $newPaths = [];
        foreach ($files as $file) {
            $newPaths[] = $file->store('bukti_bosq', 'public');
        }

        $allPaths = array_merge($existing, $newPaths);
        $encoded  = json_encode(array_values($allPaths));

        BosqTindakLanjut::where('bosq_temuan_id', $this->bosqTemuanId)->update([
            'foto_bukti_path' => $encoded,
        ]);

        $this->foto_bukti_path = $encoded;
        $this->foto_bukti      = [];

        session()->flash('foto_success', 'File bukti berhasil diunggah.');
        $this->dispatch('tindakLanjutUpdated');
    }

    public function hapusFotoBukti(int $index): void
    {
        if (!$this->authorizeAuditee()) return;

        $paths = $this->getBuktiPaths();
        if (isset($paths[$index])) {
            $file = $paths[$index];
            if (Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
            unset($paths[$index]);
            $paths   = array_values($paths);
            $encoded = empty($paths) ? null : json_encode($paths);

            BosqTindakLanjut::where('bosq_temuan_id', $this->bosqTemuanId)->update([
                'foto_bukti_path' => $encoded,
            ]);
            $this->foto_bukti_path = $encoded;

            session()->flash('foto_success', 'File bukti berhasil dihapus.');
            $this->dispatch('tindakLanjutUpdated');
        }
    }

    public function ubahStatus(string $statusBaru): void
    {
        if (!$this->authorizeAuditee()) return;

        if (!in_array($statusBaru, $this->allowedStatuses)) {
            session()->flash('status_error', 'Status tidak valid untuk Auditee.');
            return;
        }

        $tl = BosqTindakLanjut::where('bosq_temuan_id', $this->bosqTemuanId)->first();
        if (!$tl) {
            session()->flash('status_error', 'Data tindak lanjut tidak ditemukan.');
            return;
        }

        $currentOrder = $this->statusOrder[$tl->status] ?? 0;
        $newOrder     = $this->statusOrder[$statusBaru] ?? 0;

        if ($newOrder > $currentOrder + 1) {
            session()->flash('status_error', 'Transisi status tidak boleh meloncat. Lakukan secara berurutan.');
            return;
        }

        if ($newOrder < $currentOrder) {
            session()->flash('status_error', 'Status tidak bisa diundur.');
            return;
        }

        if ($statusBaru === 'closed_pending_qa') {
            if (empty($tl->action) || empty($tl->due_date)) {
                session()->flash('status_error', 'Lengkapi tindakan dan due date terlebih dahulu.');
                return;
            }
            if (empty($this->getBuktiPaths())) {
                session()->flash('status_error', 'Foto/File bukti WAJIB diupload minimal 1 sebelum status Closed Pending QA.');
                return;
            }
        }

        $tl->update(['status' => $statusBaru]);
        BosqTemuan::where('id', $this->bosqTemuanId)->update(['status' => $statusBaru]);

        $this->currentStatus = $statusBaru;
        $this->status        = $statusBaru;

        if ($statusBaru === 'closed_pending_qa') {
            $this->kirimNotifikasiQA();
        }

        session()->flash('status_success', 'Status berhasil diubah ke: ' . $this->statusLabel($statusBaru));
        $this->dispatch('tindakLanjutUpdated');
    }

    protected function kirimNotifikasiQA(): void
    {
        $temuan = BosqTemuan::with(['departemen', 'auditee', 'elemenQfs'])->find($this->bosqTemuanId);
        if (!$temuan) return;

        $qaUsers = User::where('role', 'qa')->get();
        if ($qaUsers->isEmpty()) return;

        $link  = route('bosq.temuan.detail', $temuan->id);
        $pesan = "*[BOS'Q] Tindak Lanjut Siap Diverifikasi QA*\n\n"
               . "📋 *ID*: #{$temuan->id}\n"
               . "🏢 *Dept*: " . ($temuan->departemen->nama_departemen ?? '-') . "\n"
               . "🎯 *Elemen QFS*: " . ($temuan->elemenQfs->nama_elemen ?? '-') . "\n"
               . "👤 *Auditee*: " . ($temuan->auditee->name ?? '-') . "\n\n"
               . "Mohon lakukan verifikasi di:\n{$link}";

        $emailService = app(\App\Services\EmailNotificationService::class);
        foreach ($qaUsers as $qa) {
            if ($qa->email) {
                $emailService->sendBosqNotification($temuan, 'subarea_pic', $qa->email);
            }
        }
    }

    public function statusLabel(string $status): string
    {
        return match ($status) {
            'open'              => 'Open',
            'in_progress'       => 'In Progress',
            'closed_pending_qa' => 'Closed (Pending QA)',
            'closed_acc'        => 'Closed ACC (Selesai)',
            default             => ucfirst($status),
        };
    }

    public function render()
    {
        $this->loadTindakLanjut();

        return view('livewire.bosq.tindak-lanjut-auditee', [
            'buktiPaths' => $this->getBuktiPaths(),
        ]);
    }
}
