<?php

namespace App\Livewire\BosQ;

use App\Models\BosqTemuan;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarTemuanPelapor extends Component
{
    use WithPagination;

    public string $filterStatus = ''; // '', 'open', 'closed'

    public function setFilterStatus(string $status): void
    {
        if ($this->filterStatus === $status) {
            $this->filterStatus = '';
        } else {
            $this->filterStatus = $status;
        }
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

        if ($this->filterStatus !== '') {
            if ($this->filterStatus === 'open') {
                $query->whereIn('status', ['open', 'in_progress', 'closed_pending_qa']);
            } elseif ($this->filterStatus === 'closed') {
                $query->whereIn('status', ['closed', 'closed_acc']);
            }
        }

        $temuans = $query->latest('tanggal_temuan')->latest('id')->paginate(10);

        return view('livewire.bosq.daftar-temuan-pelapor', [
            'temuans' => $temuans,
        ]);
    }
}
