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
        
        // 1. Departemen Chart
        $chartDataGrouped = $allFiltered->groupBy('departemen_id')->mapWithKeys(function ($group) {
            $deptName = $group->first()->departemen->nama_departemen ?? 'Tidak Diketahui';
            return [$deptName => $group->count()];
        });

        // 2. Klausul Chart
        $chartKlausulGrouped = $allFiltered->groupBy('klausul_id')->mapWithKeys(function ($group) {
            $klausul = $group->first()->klausul;
            $klausulLabel = $klausul ? $klausul->kode_klausul : 'Belum Ditentukan';
            return [$klausulLabel => $group->count()];
        });

        // 3. Status Chart
        $chartStatusGrouped = [
            'Open' => $allFiltered->where('status', 'open')->count(),
            'In Progress' => $allFiltered->where('status', 'in_progress')->count(),
            'Pending QA' => $allFiltered->where('status', 'closed_pending_qa')->count(),
            'Closed (ACC)' => $allFiltered->where('status', 'closed_acc')->count(),
        ];

        $this->dispatch('chart-updated', [
            'deptLabels' => $chartDataGrouped->keys()->toJson(), 
            'deptData' => $chartDataGrouped->values()->toJson(),
            'klausulLabels' => $chartKlausulGrouped->keys()->toJson(),
            'klausulData' => $chartKlausulGrouped->values()->toJson(),
            'statusLabels' => json_encode(array_keys($chartStatusGrouped)),
            'statusData' => json_encode(array_values($chartStatusGrouped)),
        ]);

        $departemens = Departemen::orderBy('nama_departemen')->get();
        $temuans = $query->paginate(15);

        return view('livewire.daftar-temuan-q-a', [
            'temuans' => $temuans,
            'departemens' => $departemens,
            'chartLabels' => $chartDataGrouped->keys()->toJson(),
            'chartData' => $chartDataGrouped->values()->toJson(),
            'klausulLabels' => $chartKlausulGrouped->keys()->toJson(),
            'klausulData' => $chartKlausulGrouped->values()->toJson(),
            'statusLabels' => json_encode(array_keys($chartStatusGrouped)),
            'statusData' => json_encode(array_values($chartStatusGrouped)),
        ]);
    }
}
