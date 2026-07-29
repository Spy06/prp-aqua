<?php

namespace App\Livewire\BosQ;

use App\Models\BosqElemenQfs;
use App\Models\BosqLine;
use App\Models\BosqSubArea;
use App\Models\BosqTemuan;
use App\Models\Departemen;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardQA extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    // Filter Periode
    public string $filter_type = 'bulan'; // 'bulan', 'tahun', 'custom'
    public int $bulan;
    public int $tahun;
    public string $tgl_mulai = '';
    public string $tgl_selesai = '';

    // Filter Tabel Temuan
    public string $filter_departemen_id = '';
    public string $filter_sub_area_id = '';
    public string $filter_status = '';
    public string $filter_tingkat_resiko = '';
    public string $filter_dampak_temuan = '';

    public function mount(): void
    {
        $this->bulan = (int) now()->month;
        $this->tahun = (int) now()->year;
        $this->tgl_mulai = Carbon::now()->startOfMonth()->toDateString();
        $this->tgl_selesai = Carbon::now()->toDateString();
    }

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function updatingBulan(): void
    {
        $this->resetPage();
    }

    public function updatingTahun(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDepartemenId(): void
    {
        $this->resetPage();
    }

    public function updatingFilterSubAreaId(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTingkatResiko(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDampakTemuan(): void
    {
        $this->resetPage();
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

        // Base Query untuk Periode
        $baseQuery = BosqTemuan::with(['departemen', 'subArea', 'elemenQfs', 'pelapor', 'auditee', 'tindakLanjut'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir]);

        $allTemuan = (clone $baseQuery)->get();

        // 1. Grafik Status BQA (Open vs Closed)
        $chartStatusData = [
            'Open'   => $allTemuan->whereIn('status', ['open', 'in_progress', 'closed_pending_qa'])->count(),
            'Closed' => $allTemuan->whereIn('status', ['closed', 'closed_acc'])->count(),
        ];

        // 2. Grafik Temuan per Departemen (Bar Chart)
        $departemens = Departemen::orderBy('nama_departemen')->get();
        $chartDeptGrouped = $allTemuan->groupBy('departemen_id')->mapWithKeys(function ($group) {
            $deptName = $group->first()->departemen->nama_departemen ?? 'Lainnya';
            return [$deptName => $group->count()];
        });
        
        // Pastikan semua departemen terisi meski 0
        $chartDeptData = [];
        foreach ($departemens as $d) {
            $chartDeptData[$d->nama_departemen] = $chartDeptGrouped->get($d->nama_departemen, 0);
        }

        // 3. Grafik Dampak Observasi (Negatif vs Positif)
        $chartDampakData = [
            'Negatif (Butuh Perbaikan)' => $allTemuan->where('dampak_temuan', 'negatif')->count(),
            'Positif (Perilaku Baik)'  => $allTemuan->where('dampak_temuan', 'positif')->count(),
        ];

        // Query Tabel Temuan dengan Filter Tambahan
        $tableQuery = clone $baseQuery;

        if ($this->filter_departemen_id !== '') {
            $tableQuery->where('departemen_id', $this->filter_departemen_id);
        }

        if ($this->filter_sub_area_id !== '') {
            $tableQuery->where('sub_area_id', $this->filter_sub_area_id);
        }

        if ($this->filter_status !== '') {
            if ($this->filter_status === 'open') {
                $tableQuery->whereIn('status', ['open', 'in_progress', 'closed_pending_qa']);
            } else {
                $tableQuery->whereIn('status', ['closed', 'closed_acc']);
            }
        }

        if ($this->filter_tingkat_resiko !== '') {
            $tableQuery->where('tingkat_resiko', $this->filter_tingkat_resiko);
        }

        if ($this->filter_dampak_temuan !== '') {
            $tableQuery->where('dampak_temuan', $this->filter_dampak_temuan);
        }

        $temuans = $tableQuery->orderBy('tanggal_temuan', 'desc')->paginate(10);

        $subAreas = $this->filter_departemen_id
            ? BosqSubArea::where('departemen_id', $this->filter_departemen_id)->orWhereNull('departemen_id')->orderBy('nama_sub_area')->get()
            : BosqSubArea::orderBy('nama_sub_area')->get();

        return view('livewire.bosq.dashboard-q-a', [
            'filterLabel'      => $filter['label'],
            'totalTemuan'      => $allTemuan->count(),
            'totalOpen'        => $chartStatusData['Open'],
            'totalClosed'      => $chartStatusData['Closed'],
            'totalNegatif'     => $chartDampakData['Negatif (Butuh Perbaikan)'],
            'totalPositif'     => $chartDampakData['Positif (Perilaku Baik)'],
            'chartStatusData'  => $chartStatusData,
            'chartDeptData'    => $chartDeptData,
            'chartDampakData'  => $chartDampakData,
            'temuans'          => $temuans,
            'departemens'      => $departemens,
            'subAreas'         => $subAreas,
        ])->layout('layouts.bosq', ['title' => 'Dashboard QA — BOS\'Q']);
    }
}
