<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\Temuan;
use Illuminate\Support\Carbon;
use Livewire\Component;

class GrafikTemuan extends Component
{
    // Filter Periode
    public string $filter_type = 'bulan'; // 'bulan', 'tahun', 'custom'
    public int $bulan = 1;
    public int $tahun = 2026;
    public string $tgl_mulai = '';
    public string $tgl_selesai = '';

    // Filter Sub Area Chart
    public $filterDepartemenSubArea = '';

    public function mount(): void
    {
        $this->bulan = (int) now()->month;
        $this->tahun = (int) now()->year;
        $this->tgl_mulai = Carbon::now()->startOfMonth()->toDateString();
        $this->tgl_selesai = Carbon::now()->toDateString();

        $firstDept = Departemen::orderBy('nama_departemen')->first();
        if ($firstDept) {
            $this->filterDepartemenSubArea = $firstDept->id;
        }
    }

    public function updatingFilterType(): void {}
    public function updatingBulan(): void {}
    public function updatingTahun(): void {}
    public function updatingTglMulai(): void {}
    public function updatingTglSelesai(): void {}
    public function updatingFilterDepartemenSubArea(): void {}

    public function parseFilter(): array
    {
        $tahun = (int) ($this->tahun ?: now()->year);
        $bulan = (int) ($this->bulan ?: now()->month);
        if ($bulan < 1 || $bulan > 12) {
            $bulan = (int) now()->month;
        }

        return match($this->filter_type) {
            'custom' => [
                'awal'  => $this->tgl_mulai ?: Carbon::now()->startOfMonth()->toDateString(),
                'akhir' => $this->tgl_selesai ?: Carbon::now()->toDateString(),
                'label' => ($this->tgl_mulai ?: '-') . ' s/d ' . ($this->tgl_selesai ?: '-'),
            ],
            'tahun' => [
                'awal'  => Carbon::createFromDate($tahun, 1, 1)->startOfYear()->toDateString(),
                'akhir' => Carbon::createFromDate($tahun, 1, 1)->endOfYear()->toDateString(),
                'label' => "Tahun {$tahun}",
            ],
            default => [ // 'bulan'
                'awal'  => Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString(),
                'akhir' => Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString(),
                'label' => Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y'),
            ],
        };
    }

    public function render()
    {
        $filter = $this->parseFilter();
        $awal = $filter['awal'];
        $akhir = $filter['akhir'];

        $allTemuan = Temuan::with(['departemen', 'klausul'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir])
            ->get();

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

        // 4. Sub Area Chart (Filtered by Departemen & Date Range - Top 10 Sub Areas)
        $subAreaQuery = Temuan::query()->whereBetween('tanggal_temuan', [$awal, $akhir]);
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
            'filterLabel'    => $filter['label'],
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
