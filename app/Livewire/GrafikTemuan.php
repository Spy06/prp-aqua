<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\Temuan;
use Livewire\Component;

class GrafikTemuan extends Component
{
    public $filterDepartemenSubArea = '';

    public function mount()
    {
        $firstDept = Departemen::orderBy('nama_departemen')->first();
        if ($firstDept) {
            $this->filterDepartemenSubArea = $firstDept->id;
        }
    }

    public function render()
    {
        $allTemuan = Temuan::with(['departemen', 'klausul'])->get();

        // 1. Departemen Chart
        $chartDataGrouped = $allTemuan->groupBy('departemen_id')->mapWithKeys(function ($group) {
            $deptName = $group->first()->departemen->nama_departemen ?? 'Tidak Diketahui';
            $deptName = match(trim($deptName)) {
                'Safety Health & Environment' => 'SHE',
                'Safety Health & Environment (SHE)' => 'SHE',
                'Engineering' => 'ENG',
                'Corporate Social Responsibility' => 'CSR',
                'Logistics' => 'LOG',
                'Human Resource' => 'HR',
                'Quality Assurance' => 'QA',
                'Manufacturing' => 'MFG',
                default => $deptName
            };
            return [$deptName => $group->count()];
        });

        // 2. Klausul Chart
        $chartKlausulGrouped = $allTemuan->groupBy('klausul_id')->mapWithKeys(function ($group) {
            $klausul = $group->first()->klausul;
            $klausulLabel = $klausul ? $klausul->kode_klausul : 'Belum Ditentukan';
            return [$klausulLabel => $group->count()];
        })->sortBy(function ($count, $key) {
            return (int) $key;
        });

        // 3. Status Chart
        $chartStatusGrouped = [
            'Open' => $allTemuan->where('status', 'open')->count(),
            'In Progress' => $allTemuan->where('status', 'in_progress')->count(),
            'Pending QA' => $allTemuan->where('status', 'closed_pending_qa')->count(),
            'Closed (ACC)' => $allTemuan->where('status', 'closed_acc')->count(),
        ];

        // 4. Sub Area Chart (Filtered by Departemen - Top 10 Sub Areas)
        $subAreaQuery = Temuan::query();
        if ($this->filterDepartemenSubArea) {
            $subAreaQuery->where('departemen_id', $this->filterDepartemenSubArea);
        }
        $subAreaFiltered = $subAreaQuery->get();
        $chartSubAreaGrouped = $subAreaFiltered->groupBy('sub_area')
            ->mapWithKeys(function ($group) {
                $rawName = $group->first()->sub_area ?? 'N/A';
                $shortName = str_replace('Area Dummy ', 'Area ', $rawName);
                return [$shortName => $group->count()];
            })
            ->sortByDesc(function ($count) {
                return $count;
            })
            ->take(10);


        $this->dispatch('chart-updated', [
            'deptLabels' => $chartDataGrouped->keys()->toJson(), 
            'deptData' => $chartDataGrouped->values()->toJson(),
            'klausulLabels' => $chartKlausulGrouped->keys()->toJson(),
            'klausulData' => $chartKlausulGrouped->values()->toJson(),
            'statusLabels' => json_encode(array_keys($chartStatusGrouped)),
            'statusData' => json_encode(array_values($chartStatusGrouped)),
            'subAreaLabels' => $chartSubAreaGrouped->keys()->toJson(),
            'subAreaData' => $chartSubAreaGrouped->values()->toJson(),
        ]);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('livewire.grafik-temuan', [
            'departemens'    => $departemens,
            'chartLabels'    => $chartDataGrouped->keys()->toJson(),
            'chartData'      => $chartDataGrouped->values()->toJson(),
            'klausulLabels'  => $chartKlausulGrouped->keys()->toJson(),
            'klausulData'    => $chartKlausulGrouped->values()->toJson(),
            'statusLabels'   => json_encode(array_keys($chartStatusGrouped)),
            'statusData'     => json_encode(array_values($chartStatusGrouped)),
            'subAreaLabels'  => $chartSubAreaGrouped->keys()->toJson(),
            'subAreaData'    => $chartSubAreaGrouped->values()->toJson(),
            // Stat counts for cards
            'totalTemuan'    => $allTemuan->count(),
            'totalOpen'      => $chartStatusGrouped['Open'],
            'totalInProgress'=> $chartStatusGrouped['In Progress'],
            'totalPendingQa' => $chartStatusGrouped['Pending QA'],
            'totalClosedAcc' => $chartStatusGrouped['Closed (ACC)'],
        ]);
    }
}
