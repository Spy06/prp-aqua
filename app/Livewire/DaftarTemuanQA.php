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

        // For the chart, we get all filtered data without pagination
        $allFiltered = (clone $query)->get();
        $chartDataGrouped = $allFiltered->groupBy('departemen_id')->mapWithKeys(function ($group) {
            $deptName = $group->first()->departemen->nama_departemen ?? 'Tidak Diketahui';
            return [$deptName => $group->count()];
        });

        $this->dispatch('chart-updated', 
            labels: $chartDataGrouped->keys()->toJson(), 
            data: $chartDataGrouped->values()->toJson()
        );

        $departemens = Departemen::orderBy('nama_departemen')->get();
        $temuans = $query->paginate(15);

        return view('livewire.daftar-temuan-q-a', [
            'temuans' => $temuans,
            'departemens' => $departemens,
            'chartLabels' => $chartDataGrouped->keys()->toJson(),
            'chartData' => $chartDataGrouped->values()->toJson(),
        ]);
    }
}
