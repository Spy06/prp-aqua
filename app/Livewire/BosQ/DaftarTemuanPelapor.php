<?php

namespace App\Livewire\BosQ;

use App\Models\BosqTemuan;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarTemuanPelapor extends Component
{
    use WithPagination;

    public string $filterStatus = '';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    #[On('temuanAdded')]
    public function refresh(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BosqTemuan::with(['departemen', 'line', 'subArea', 'elemenQfs', 'auditee', 'tindakLanjut'])
            ->where('pelapor_id', auth()->id());

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $temuans = $query->latest('tanggal_temuan')->latest('id')->paginate(10);

        return view('livewire.bosq.daftar-temuan-pelapor', [
            'temuans' => $temuans,
        ]);
    }
}
