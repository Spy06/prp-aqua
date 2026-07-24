<?php

namespace App\Livewire\BosQ;

use App\Models\BosqTemuan;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarTemuanAuditee extends Component
{
    use WithPagination;

    public string $filterStatus = '';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    #[On('tindakLanjutUpdated')]
    public function refresh(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BosqTemuan::with(['departemen', 'line', 'subArea', 'elemenQfs', 'pelapor', 'tindakLanjut'])
            ->where('auditee_id', auth()->id())
            ->where('dampak_temuan', 'negatif'); // hanya negatif yang perlu tindak lanjut

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $temuans = $query->latest('tanggal_temuan')->latest('id')->paginate(10);

        return view('livewire.bosq.daftar-temuan-auditee', [
            'temuans' => $temuans,
        ]);
    }
}
