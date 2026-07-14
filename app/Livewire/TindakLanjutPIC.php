<?php

namespace App\Livewire;

use App\Jobs\SendWhatsAppDummy;
use App\Models\KlausulPrp;
use App\Models\TindakLanjut;
use App\Models\Temuan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class TindakLanjutPIC extends Component
{
    use WithFileUploads;

    // Temuan yang sedang dibuka (di-pass dari DetailTemuan)
    #[Locked]
    public int $temuanId;

    // State form tindak lanjut
    public string  $action       = '';
    public ?string $due_date     = null;
    public         $foto_bukti   = null; // Livewire temporary upload
    public string  $status       = 'open';

    // Untuk menampilkan existing foto
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
        $this->authorizePic();
        $this->loadTindakLanjut();
    }

    protected function authorizePic(): void
    {
        $temuan = Temuan::findOrFail($this->temuanId);
        if (auth()->id() !== $temuan->pic_id || auth()->user()->role === 'qa') {
            abort(403, 'Hanya PIC dengan role non-QA yang dapat mengakses form tindak lanjut ini.');
        }
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

    /**
     * Validasi dan simpan perubahan tindak lanjut (klausul, action, due date)
     * tanpa mengubah status.
     */
    public function simpanDetail(): void
    {
        $this->authorizePic();
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

    /**
     * Upload foto bukti.
     */
    public function uploadFoto(): void
    {
        $this->authorizePic();
        $this->validate([
            'foto_bukti' => 'required|image|max:5120',
        ], [
            'foto_bukti.required' => 'Pilih file foto terlebih dahulu.',
            'foto_bukti.image'    => 'File harus berupa gambar (jpg, png, dll.).',
            'foto_bukti.max'      => 'Ukuran foto maksimal 5MB.',
        ]);

        $path = $this->foto_bukti->store('bukti', 'public');

        TindakLanjut::where('temuan_id', $this->temuanId)->update([
            'foto_bukti_path' => $path,
        ]);

        $this->foto_bukti_path = $path;
        $this->foto_bukti      = null;

        session()->flash('foto_success', 'Foto bukti berhasil diupload.');
        $this->dispatch('tindakLanjutUpdated');
    }

    /**
     * Ubah status tindak lanjut.
     * Aturan ketat:
     * - PIC TIDAK bisa set closed_acc (hanya QA)
     * - Tidak bisa loncat status (open → closed_pending_qa tanpa melewati in_progress)
     * - closed_pending_qa WAJIB ada foto_bukti terlebih dahulu
     */
    public function ubahStatus(string $statusBaru): void
    {
        $this->authorizePic();
        // 1. Validasi: PIC tidak boleh set closed_acc
        if ($statusBaru === 'closed_acc') {
            session()->flash('status_error', 'Status closed_acc hanya bisa diset oleh QA.');
            return;
        }

        // 2. Validasi: status harus ada dalam daftar yang diizinkan
        if (!in_array($statusBaru, $this->allowedStatuses, true)) {
            session()->flash('status_error', 'Status tidak valid.');
            return;
        }

        // 3. Refresh current status dari database (cegah race condition)
        $tl = TindakLanjut::where('temuan_id', $this->temuanId)->first();
        if (!$tl) {
            session()->flash('status_error', 'Data tindak lanjut tidak ditemukan.');
            return;
        }

        $currentOrder = $this->statusOrder[$tl->status] ?? 0;
        $newOrder     = $this->statusOrder[$statusBaru] ?? 0;

        // 4. Validasi: tidak boleh loncat lebih dari 1 level ke depan
        if ($newOrder > $currentOrder + 1) {
            session()->flash('status_error', 'Tidak bisa loncat status. Selesaikan tahap sebelumnya terlebih dahulu.');
            return;
        }

        // 5. Tidak boleh mundur ke status lebih rendah
        if ($newOrder < $currentOrder) {
            session()->flash('status_error', 'Status tidak bisa diundur dari ' . $tl->status . ' ke ' . $statusBaru . '.');
            return;
        }

        // 6. Jika menuju closed_pending_qa: wajib ada detail dan foto bukti
        if ($statusBaru === 'closed_pending_qa') {
            // Wajib sudah isi action & due_date
            if (empty($tl->action) || empty($tl->due_date)) {
                session()->flash('status_error', 'Lengkapi tindakan dan due date terlebih dahulu sebelum menutup laporan.');
                return;
            }

            // WAJIB ada foto bukti
            if (empty($tl->foto_bukti_path)) {
                session()->flash('status_error', 'Foto bukti WAJIB diupload sebelum status bisa diubah ke Closed Pending QA.');
                return;
            }
        }

        // 7. Simpan perubahan status ke tabel tindak_lanjut dan temuan
        $tl->update(['status' => $statusBaru]);

        Temuan::where('id', $this->temuanId)->update(['status' => $statusBaru]);

        $this->currentStatus = $statusBaru;
        $this->status        = $statusBaru;

        // 8. Jika statusBaru = closed_pending_qa → kirim notifikasi WA ke QA
        if ($statusBaru === 'closed_pending_qa') {
            $this->kirimNotifikasiQA();
        }

        session()->flash('status_success', 'Status berhasil diubah ke: ' . $this->statusLabel($statusBaru));
        $this->dispatch('tindakLanjutUpdated');
    }

    /**
     * Kirim notifikasi WA ke semua user berole 'qa'.
     */
    protected function kirimNotifikasiQA(): void
    {
        $qaUsers = User::where('role', 'qa')->get();

        foreach ($qaUsers as $qa) {
            if ($qa->no_whatsapp) {
                $link    = route('temuan.detail', ['temuan' => $this->temuanId]);
                $message = "Temuan #{$this->temuanId} sudah ditindaklanjuti PIC dan menunggu verifikasi Anda. "
                         . "Silakan cek: {$link}";

                SendWhatsAppDummy::dispatch($qa->no_whatsapp, $message);
            }
        }
    }

    protected function statusLabel(string $status): string
    {
        return match($status) {
            'open'               => 'Open',
            'in_progress'        => 'In Progress',
            'closed_pending_qa'  => 'Closed Pending QA',
            'closed_acc'         => 'Closed (ACC)',
            default              => $status,
        };
    }

    public function render()
    {
        // Refresh foto_bukti_path dari DB agar selalu up to date
        $tl = TindakLanjut::where('temuan_id', $this->temuanId)->first();
        if ($tl) {
            $this->foto_bukti_path = $tl->foto_bukti_path;
            $this->currentStatus   = $tl->status;
            $this->status          = $tl->status;
        }

        return view('livewire.tindak-lanjut-pic', [
            'tindakLanjut' => $tl,
        ]);
    }
}
