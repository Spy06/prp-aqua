<?php

namespace App\Livewire;

use App\Models\Temuan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class DetailTemuan extends Component
{
    use AuthorizesRequests;

    public Temuan $temuan;

    public function mount(Temuan $temuan): void
    {
        // WAJIB: otorisasi level-objek via Policy
        // Hanya pelapor_id, pic_id dari temuan ini, atau role qa yang boleh masuk
        $this->authorize('view', $temuan);

        $this->temuan = $temuan;
    }

    public function render()
    {
        // Eager load relasi yang dibutuhkan
        $this->temuan->loadMissing(['departemen', 'pelapor', 'pic', 'klausul', 'tindakLanjut']);

        $user    = auth()->user();
        $temuan  = $this->temuan;

        // Tentukan panel yang harus tampil berdasarkan role user
        $isPic       = $user->id === $temuan->pic_id;
        $isPelapor   = $user->id === $temuan->pelapor_id;
        $isQa        = $user->role === 'qa';

        // PIC bisa mengisi tindak lanjut selama status belum closed_acc dan role bukan QA
        $showTindakLanjutForm = $isPic && $user->role !== 'qa' && $temuan->status !== 'closed_acc';

        return view('livewire.detail-temuan', [
            'temuan'               => $temuan,
            'isPic'                => $isPic,
            'isPelapor'            => $isPelapor,
            'isQa'                 => $isQa,
            'showTindakLanjutForm' => $showTindakLanjutForm,
        ]);
    }
}
