<?php

namespace App\Livewire;

use App\Models\Temuan;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarTemuanPelapor extends Component
{
    use WithPagination;

    #[On('temuanAdded')]
    public function render()
    {
        // Hitung metrics langsung di tingkat database
        $metrics = [
            'open' => Temuan::where('pelapor_id', auth()->id())->where('status', 'open')->count(),
            'in_progress' => Temuan::where('pelapor_id', auth()->id())->where('status', 'in_progress')->count(),
            'pending_qa' => Temuan::where('pelapor_id', auth()->id())->where('status', 'closed_pending_qa')->count(),
            'closed' => Temuan::where('pelapor_id', auth()->id())->where('status', 'closed_acc')->count(),
        ];

        // Ambil data dengan Pagination (hanya 10 per halaman)
        $temuans = Temuan::with(['departemen', 'pic'])
            ->where('pelapor_id', auth()->id())
            ->orderByRaw("
                CASE 
                    WHEN status = 'open' THEN 1
                    WHEN status = 'in_progress' THEN 2
                    WHEN status = 'closed_pending_qa' THEN 3
                    ELSE 4
                END ASC,
                created_at DESC
            ")
            ->paginate(10);

        return view('livewire.daftar-temuan-pelapor', [
            'temuans' => $temuans,
            'metrics' => $metrics,
        ]);
    }
}
