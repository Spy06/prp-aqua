<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\Temuan;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarTemuanQA extends Component
{
    use WithPagination;

    public $filterDepartemen = '';
    public $filterStatus = '';

    public function updatingFilterDepartemen()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Temuan::with(['departemen', 'pic'])->orderBy('tanggal_temuan', 'desc');

        if ($this->filterDepartemen) {
            $query->where('departemen_id', $this->filterDepartemen);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $departemens = Departemen::orderBy('nama_departemen')->get();
        $temuans = $query->paginate(15);

        return view('livewire.daftar-temuan-q-a', [
            'temuans' => $temuans,
            'departemens' => $departemens,
        ]);
    }
}
