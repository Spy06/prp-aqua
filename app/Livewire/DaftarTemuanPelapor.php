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
        $temuans = Temuan::with(['departemen', 'pic'])
            ->where('pelapor_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate metrics
        $metrics = [
            'open' => $temuans->where('status', 'open')->count(),
            'in_progress' => $temuans->where('status', 'in_progress')->count(),
            'pending_qa' => $temuans->where('status', 'closed_pending_qa')->count(),
            'closed' => $temuans->where('status', 'closed_acc')->count(),
        ];

        return view('livewire.daftar-temuan-pelapor', [
            'temuans' => $temuans,
            'metrics' => $metrics,
        ]);
    }
}
