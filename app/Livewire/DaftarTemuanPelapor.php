<?php

namespace App\Livewire;

use App\Models\Temuan;
use Livewire\Attributes\On;
use Livewire\Component;

class DaftarTemuanPelapor extends Component
{
    #[On('temuanAdded')]
    public function render()
    {
        // Ambil temuan khusus milik user yang sedang login
        $temuans = Temuan::with('departemen')
            ->where('pelapor_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.daftar-temuan-pelapor', [
            'temuans' => $temuans,
        ]);
    }
}
