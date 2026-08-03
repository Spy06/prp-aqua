<?php

namespace App\Livewire;

use App\Jobs\SendWhatsApp;
use App\Models\Temuan;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class TindakLanjutPIC extends Component
{
    use WithFileUploads;

    public int $temuanId;

    // Field form tindak lanjut
    public string  $action      = '';
    public ?string $due_date    = null;
    public         $foto_bukti  = []; // Livewire temporary upload (array or single)
    public string  $status      = 'open';

    // Untuk menampilkan existing foto / file bukti (tersimpan sebagai JSON array atau string)
    public ?string $foto_bukti_path = null;

    // Status saat ini dari database (referensi, tidak bisa diubah langsung)
    #[Locked]
    public string $currentStatus = 'open';

    /**
     * Status yang diizinkan untuk PIC (closed_acc TIDAK termasuk — hanya QA).
     */
    protected array $allowedStatuses = ['open', 'in_progress', 'closed_pending_qa'];

    /**
     * Urutan transisi status yang valid (tidak bisa loncat).
     */
    protected array $statusOrder = [
        'open'              => 0,
        'in_progress'       => 1,
        'closed_pending_qa' => 2,
        'closed_acc'        => 3, // QA only, PIC tidak bisa set ini
    ];

    public function mount(int $temuanId): void
    {
        $this->temuanId = $temuanId;
        $this->loadTindakLanjut();
    }

    protected function authorizePic(): bool
    {
        $temuan = Temuan::findOrFail($this->temuanId);
        if (auth()->id() !== $temuan->pic_id || auth()->user()->role === 'qa') {
            session()->flash('status_error', 'Akses ditolak: hanya PIC karyawan yang dapat mengubah status temuan ini.');
            return false;
        }
        return true;
    }

    protected function loadTindakLanjut(): void
    {
        $tl = TindakLanjut::where('temuan_id', $this->temuanId)->first();

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
        if (empty($this->foto_bukti_path)) {
            return [];
        }
        $decoded = json_decode($this->foto_bukti_path, true);
        if (is_array($decoded)) {
            return array_values($decoded);
        }
        return [$this->foto_bukti_path];
    }

    public function simpanDetail(): void
    {
        if (!$this->authorizePic()) return;
        $this->validate([
            'action'     => 'required|string|max:2000',
            'due_date'   => 'required|date',
        ], [
            'action.required'     => 'Deskripsi tindakan wajib diisi.',
            'due_date.required'   => 'Due date wajib diisi.',
        ]);

        TindakLanjut::where('temuan_id', $this->temuanId)->update([
            'action'     => $this->action,
            'due_date'   => $this->due_date,
        ]);

        session()->flash('detail_success', 'Detail tindak lanjut berhasil disimpan.');
        $this->dispatch('tindakLanjutUpdated');
    }

    public function updatedFotoBukti(): void
    {
        if (!$this->authorizePic()) return;
        $this->uploadFoto();
    }

    public function uploadFoto(): void
    {
        if (!$this->authorizePic()) return;

        $files = is_array($this->foto_bukti) ? $this->foto_bukti : [$this->foto_bukti];
        $files = array_filter($files);

        if (empty($files)) {
            $this->validate([
                'foto_bukti' => 'required',
            ], [
                'foto_bukti.required' => 'Pilih file bukti terlebih dahulu.',
            ]);
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
                'foto_bukti.*.file'     => 'File yang dipilih tidak valid.',
                'foto_bukti.*.mimes'    => 'Format file harus berupa gambar (JPG, PNG, WEBP) atau dokumen (PDF, DOC, DOCX).',
                'foto_bukti.*.max'      => 'Ukuran setiap file maksimal 3MB.',
                'foto_bukti.*.uploaded' => 'Gagal mengunggah file. Pastikan ukuran file maksimal 3MB dan formatnya sesuai (Gambar/PDF/Word).',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->foto_bukti = [];
            throw $e;
        }

        $newPaths = [];
        foreach ($files as $file) {
            $newPaths[] = $file->store('bukti', 'public');
        }

        $allPaths = array_merge($existing, $newPaths);
        $encoded = json_encode(array_values($allPaths));

        TindakLanjut::where('temuan_id', $this->temuanId)->update([
            'foto_bukti_path' => $encoded,
        ]);

        $this->foto_bukti_path = $encoded;
        $this->foto_bukti      = [];

        session()->flash('foto_success', 'File bukti berhasil diunggah.');
        $this->dispatch('tindakLanjutUpdated');
    }

    public function hapusFotoBukti(int $index): void
    {
        if (!$this->authorizePic()) return;

        $paths = $this->getBuktiPaths();
        if (isset($paths[$index])) {
            $fileToDelete = $paths[$index];
            if (Storage::disk('public')->exists($fileToDelete)) {
                Storage::disk('public')->delete($fileToDelete);
            }

            unset($paths[$index]);
            $paths = array_values($paths);

            $encoded = empty($paths) ? null : json_encode($paths);

            TindakLanjut::where('temuan_id', $this->temuanId)->update([
                'foto_bukti_path' => $encoded,
            ]);

            $this->foto_bukti_path = $encoded;
            session()->flash('foto_success', 'File bukti berhasil dihapus.');
            $this->dispatch('tindakLanjutUpdated');
        }
    }

    public function ubahStatus(string $statusBaru): void
    {
        if (!$this->authorizePic()) return;

        if (!in_array($statusBaru, $this->allowedStatuses)) {
            session()->flash('status_error', 'Status tidak valid untuk PIC.');
            return;
        }

        $tl = TindakLanjut::where('temuan_id', $this->temuanId)->first();
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
            session()->flash('status_error', 'Status tidak bisa diundur dari ' . $tl->status . ' ke ' . $statusBaru . '.');
            return;
        }

        if ($statusBaru === 'closed_pending_qa') {
            if (empty($tl->action) || empty($tl->due_date)) {
                session()->flash('status_error', 'Lengkapi tindakan dan due date terlebih dahulu sebelum menutup laporan.');
                return;
            }

            if (empty($this->getBuktiPaths())) {
                session()->flash('status_error', 'File/Foto bukti WAJIB diupload (minimal 1 file) sebelum status bisa diubah ke Closed Pending QA.');
                return;
            }
        }

        $tl->update(['status' => $statusBaru]);
        Temuan::where('id', $this->temuanId)->update(['status' => $statusBaru]);

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
        $temuan = Temuan::with(['departemen', 'pic', 'pelapor'])->find($this->temuanId);
        if (!$temuan) return;

        $emailService = app(\App\Services\EmailNotificationService::class);
        $emailService->sendSiveraNotification($temuan, 'bukti');

        $qaUsers = User::where('role', 'qa')->get();
        $deptNama = $temuan->departemen->nama_departemen ?? '-';
        $picNama  = $temuan->pic->name ?? '-';
        $pesan    = "*[SIVERA] Tindak Lanjut Siap Diverifikasi QA*\n\n"
            . "Status temuan berikut telah diubah oleh PIC menjadi *Closed (Pending QA)*:\n\n"
            . "📌 *ID Temuan*: #" . $temuan->id . "\n"
            . "🏢 *Departemen*: " . $deptNama . "\n"
            . "📍 *Sub Area*: " . $temuan->sub_area . "\n"
            . "👤 *PIC*: " . $picNama . "\n"
            . "📝 *Deskripsi Temuan*: " . $temuan->deskripsi . "\n\n"
            . "Mohon masuk ke aplikasi SIVERA untuk melakukan verifikasi dan verifikasi akhir.";

        foreach ($qaUsers as $qa) {
            if (!empty($qa->no_whatsapp)) {
                SendWhatsApp::dispatch($qa->no_whatsapp, $pesan);
            }
            if (!empty($qa->email)) {
                $emailService->sendSiveraNotification($temuan, 'bukti', $qa->email);
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

        return view('livewire.tindak-lanjut-pic', [
            'buktiPaths' => $this->getBuktiPaths(),
        ]);
    }
}
