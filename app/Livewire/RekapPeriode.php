<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\Temuan;
use Livewire\Component;
use Illuminate\Support\Carbon;

class RekapPeriode extends Component
{
    // Tipe filter: 'custom' | 'bulan' | 'tahun'
    public string $filterTipe = 'bulan';

    // Filter custom range
    public ?string $tanggalAwal = null;
    public ?string $tanggalAkhir = null;

    // Filter per bulan
    public string $filterBulan;
    public string $filterBulanTahun;

    // Filter per tahun
    public string $filterTahun;

    // Filter tambahan
    public string $filterDepartemen = '';
    public string $filterStatus = '';

    public function mount(): void
    {
        $now = Carbon::now();
        $this->filterBulan      = $now->format('m');
        $this->filterBulanTahun = $now->format('Y');
        $this->filterTahun      = $now->format('Y');
        $this->tanggalAwal      = $now->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir     = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    /**
     * Reset pagination saat filter berubah.
     */
    public function updatedFilterTipe(): void
    {
        // re-render otomatis
    }

    public function updatedFilterDepartemen(): void
    {
        // re-render otomatis
    }

    public function updatedFilterStatus(): void
    {
        // re-render otomatis
    }

    protected function getRangeFromFilter(): array
    {
        return match($this->filterTipe) {
            'custom' => [
                $this->tanggalAwal  ?? Carbon::now()->startOfMonth()->toDateString(),
                $this->tanggalAkhir ?? Carbon::now()->toDateString(),
            ],
            'bulan' => [
                Carbon::createFromDate($this->filterBulanTahun, $this->filterBulan, 1)->startOfMonth()->toDateString(),
                Carbon::createFromDate($this->filterBulanTahun, $this->filterBulan, 1)->endOfMonth()->toDateString(),
            ],
            'tahun' => [
                Carbon::createFromDate($this->filterTahun, 1, 1)->startOfYear()->toDateString(),
                Carbon::createFromDate($this->filterTahun, 1, 1)->endOfYear()->toDateString(),
            ],
            default => [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->toDateString(),
            ],
        };
    }

    /**
     * Build query parameter array untuk URL export (Excel & PDF),
     * agar filter periode yang aktif diteruskan ke ExportController.
     */
    public function buildQueryParams(): array
    {
        $params = match($this->filterTipe) {
            'custom' => [
                'tipe'  => 'custom',
                'awal'  => $this->tanggalAwal,
                'akhir' => $this->tanggalAkhir,
            ],
            'tahun' => [
                'tipe'  => 'tahun',
                'tahun' => $this->filterTahun,
            ],
            default => [
                'tipe'  => 'bulan',
                'bulan' => $this->filterBulan,
                'tahun' => $this->filterBulanTahun,
            ],
        };

        if ($this->filterDepartemen) {
            $params['departemen_id'] = $this->filterDepartemen;
        }

        if ($this->filterStatus) {
            $params['status'] = $this->filterStatus;
        }

        return $params;
    }

    public function render()
    {
        [$awal, $akhir] = $this->getRangeFromFilter();

        $query = Temuan::with(['departemen', 'tindakLanjut'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir]);

        // Base data untuk period ini (termasuk filter departemen & status jika dipilih)
        $baseQuery = clone $query;
        if ($this->filterDepartemen) {
            $baseQuery->where('departemen_id', $this->filterDepartemen);
        }
        if ($this->filterStatus) {
            $baseQuery->where('status', $this->filterStatus);
        }

        $baseTemuans = $baseQuery->get();

        // Breakdown per status (untuk area / departemen / status terpilih)
        $perStatus = [
            'open'              => $baseTemuans->where('status', 'open')->count(),
            'in_progress'       => $baseTemuans->where('status', 'in_progress')->count(),
            'closed_pending_qa' => $baseTemuans->where('status', 'closed_pending_qa')->count(),
            'closed_acc'        => $baseTemuans->where('status', 'closed_acc')->count(),
        ];

        // Breakdown per departemen
        $perDepartemen = $baseTemuans->groupBy('departemen_id')->map(function ($group) {
            $dept = $group->first()->departemen;
            return [
                'nama'  => $dept->nama_departemen ?? 'Tidak Diketahui',
                'total' => $group->count(),
                'open'              => $group->where('status', 'open')->count(),
                'in_progress'       => $group->where('status', 'in_progress')->count(),
                'closed_pending_qa' => $group->where('status', 'closed_pending_qa')->count(),
                'closed_acc'        => $group->where('status', 'closed_acc')->count(),
            ];
        })->values();

        // Filter list temuan (menggunakan departemen dan status)
        $listQuery = clone $query;
        if ($this->filterDepartemen) {
            $listQuery->where('departemen_id', $this->filterDepartemen);
        }
        if ($this->filterStatus) {
            $listQuery->where('status', $this->filterStatus);
        }

        $temuans = $listQuery->get();
        $total = $temuans->count();

        // Daftar tahun untuk dropdown (dari tahun pertama data sampai sekarang)
        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 5, -1);
        $allDepartemens = Departemen::orderBy('nama_departemen')->get();

        return view('livewire.rekap-periode', [
            'total'           => $total,
            'perStatus'       => $perStatus,
            'perDepartemen'   => $perDepartemen,
            'temuans'         => $temuans,
            'awal'            => $awal,
            'akhir'           => $akhir,
            'tahunList'       => $tahunList,
            'allDepartemens'  => $allDepartemens,
            'queryParams'     => $this->buildQueryParams(),
            'bulanList'       => [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April',   '05' => 'Mei',       '06' => 'Juni',
                '07' => 'Juli',    '08' => 'Agustus',   '09' => 'September',
                '10' => 'Oktober', '11' => 'November',  '12' => 'Desember',
            ],
        ]);
    }
}
