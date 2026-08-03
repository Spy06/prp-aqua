<?php

namespace App\Livewire\BosQ;

use App\Models\BosqElemenQfs;
use App\Models\BosqLine;
use App\Models\BosqSubArea;
use App\Models\BosqTemuan;
use App\Models\Departemen;
use Illuminate\Support\Carbon;
use Livewire\Component;

class DashboardQA extends Component
{
    // Filter Periode
    public string $filter_type = 'bulan'; // 'bulan', 'tahun', 'custom'
    public int $bulan;
    public int $tahun;
    public string $tgl_mulai = '';
    public string $tgl_selesai = '';

    // Filter Sub Area Chart
    public ?int $filterDepartemenSubArea = null;

    public function mount(): void
    {
        $this->bulan = (int) now()->month;
        $this->tahun = (int) now()->year;
        $this->tgl_mulai = Carbon::now()->startOfMonth()->toDateString();
        $this->tgl_selesai = Carbon::now()->toDateString();

        $firstDept = Departemen::orderBy('nama_departemen')->first();
        $this->filterDepartemenSubArea = $firstDept ? $firstDept->id : null;
    }

    public function parseFilter(): array
    {
        return match($this->filter_type) {
            'custom' => [
                'awal'  => $this->tgl_mulai ?: Carbon::now()->startOfMonth()->toDateString(),
                'akhir' => $this->tgl_selesai ?: Carbon::now()->toDateString(),
                'label' => ($this->tgl_mulai ?: '-') . ' s/d ' . ($this->tgl_selesai ?: '-'),
            ],
            'tahun' => [
                'awal'  => Carbon::createFromDate((int)$this->tahun, 1, 1)->startOfYear()->toDateString(),
                'akhir' => Carbon::createFromDate((int)$this->tahun, 1, 1)->endOfYear()->toDateString(),
                'label' => "Tahun {$this->tahun}",
            ],
            default => [ // 'bulan'
                'awal'  => Carbon::createFromDate((int)$this->tahun, (int)$this->bulan, 1)->startOfMonth()->toDateString(),
                'akhir' => Carbon::createFromDate((int)$this->tahun, (int)$this->bulan, 1)->endOfMonth()->toDateString(),
                'label' => Carbon::createFromDate((int)$this->tahun, (int)$this->bulan, 1)->translatedFormat('F Y'),
            ],
        };
    }

    public function render()
    {
        $filter = $this->parseFilter();
        $awal = $filter['awal'];
        $akhir = $filter['akhir'];

        $baseQuery = BosqTemuan::with(['departemen', 'subArea', 'elemenQfs', 'pelapor', 'auditee', 'tindakLanjut'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir]);

        $allTemuan = (clone $baseQuery)->get();

        // 1. Status Open vs Closed
        $chartStatusData = [
            'Open'   => $allTemuan->whereIn('status', ['open', 'in_progress', 'closed_pending_qa'])->count(),
            'Closed' => $allTemuan->whereIn('status', ['closed', 'closed_acc'])->count(),
        ];

        $departemens = Departemen::orderBy('nama_departemen')->get();
        $groupedByDept = $allTemuan->groupBy('departemen_id');

        // 2. Temuan per Departemen (HANYA departemen yang ada laporan)
        $chartDeptData = [];
        foreach ($groupedByDept as $deptId => $items) {
            $deptName = $items->first()->departemen->nama_departemen ?? 'Lainnya';
            $chartDeptData[$deptName] = $items->count();
        }

        // 3. Negatif vs Positif Berdampingan (HANYA departemen yang ada laporan)
        $chartDampakData = [
            'Negatif (Butuh Perbaikan)' => $allTemuan->where('dampak_temuan', 'negatif')->count(),
            'Positif (Perilaku Baik)'  => $allTemuan->where('dampak_temuan', 'positif')->count(),
        ];

        $chartDampakLabels = [];
        $chartDampakNegatif = [];
        $chartDampakPositif = [];

        foreach ($groupedByDept as $deptId => $items) {
            $deptName = $items->first()->departemen->nama_departemen ?? 'Lainnya';
            $chartDampakLabels[] = $deptName;
            $chartDampakNegatif[] = $items->where('dampak_temuan', 'negatif')->count();
            $chartDampakPositif[] = $items->where('dampak_temuan', 'positif')->count();
        }

        // 4. Sub Area Chart (berdasarkan filter departemen sub area)
        $subAreasList = $this->filterDepartemenSubArea
            ? BosqSubArea::where('departemen_id', $this->filterDepartemenSubArea)->orWhereNull('departemen_id')->orderBy('nama_sub_area')->get()
            : BosqSubArea::orderBy('nama_sub_area')->get();

        $subAreaLabels = [];
        $subAreaData = [];
        foreach ($subAreasList as $sa) {
            $count = $allTemuan->where('sub_area_id', $sa->id)->count();
            if ($count > 0) {
                $subAreaLabels[] = $sa->nama_sub_area;
                $subAreaData[] = $count;
            }
        }

        // 5. Grafik Temuan per Elemen QFS (HANYA elemen QFS yang ada laporan)
        $chartElemenGrouped = $allTemuan->groupBy('elemen_qfs_id');
        $elemenLabels = [];
        $elemenData = [];
        foreach ($chartElemenGrouped as $elemId => $items) {
            $elemName = $items->first()->elemenQfs->nama_elemen ?? 'Lainnya';
            $elemenLabels[] = $elemName;
            $elemenData[] = $items->count();
        }

        $this->dispatch('bosq-chart-updated', [
            'statusData'        => $chartStatusData,
            'deptData'          => $chartDeptData,
            'dampakData'        => $chartDampakData,
            'dampakLabels'      => $chartDampakLabels,
            'dampakNegatifData' => $chartDampakNegatif,
            'dampakPositifData' => $chartDampakPositif,
            'subAreaLabels'     => json_encode($subAreaLabels),
            'subAreaData'       => json_encode($subAreaData),
            'elemenLabels'      => json_encode($elemenLabels),
            'elemenData'        => json_encode($elemenData),
        ]);

        return view('livewire.bosq.dashboard-q-a', [
            'filterLabel'        => $filter['label'],
            'totalTemuan'        => $allTemuan->count(),
            'totalOpen'          => $chartStatusData['Open'],
            'totalClosed'        => $chartStatusData['Closed'],
            'totalNegatif'       => $chartDampakData['Negatif (Butuh Perbaikan)'],
            'totalPositif'       => $chartDampakData['Positif (Perilaku Baik)'],
            'chartStatusData'    => $chartStatusData,
            'chartDeptData'      => $chartDeptData,
            'chartDampakData'    => $chartDampakData,
            'chartDampakLabels'  => $chartDampakLabels,
            'chartDampakNegatif' => $chartDampakNegatif,
            'chartDampakPositif' => $chartDampakPositif,
            'subAreaLabels'      => json_encode($subAreaLabels),
            'subAreaData'        => json_encode($subAreaData),
            'elemenLabels'       => json_encode($elemenLabels),
            'elemenData'         => json_encode($elemenData),
            'departemens'        => $departemens,
        ])->layout('layouts.bosq', ['title' => 'Dashboard Analisis QA — BOS\'Q']);
    }
}
