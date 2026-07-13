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
        return match($this->filterTipe) {
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
    }

    public function render()
    {
        [$awal, $akhir] = $this->getRangeFromFilter();

        $query = Temuan::with(['departemen', 'tindakLanjut'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir]);

        $temuans = $query->get();

        // Total
        $total = $temuans->count();

        // Breakdown per status
        $perStatus = [
            'open'              => $temuans->where('status', 'open')->count(),
            'in_progress'       => $temuans->where('status', 'in_progress')->count(),
            'closed_pending_qa' => $temuans->where('status', 'closed_pending_qa')->count(),
            'closed_acc'        => $temuans->where('status', 'closed_acc')->count(),
        ];

        // Breakdown per departemen
        $perDepartemen = $temuans->groupBy('departemen_id')->map(function ($group) {
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

        // Daftar tahun untuk dropdown (dari tahun pertama data sampai sekarang)
        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 5, -1);

        return view('livewire.rekap-periode', [
            'total'         => $total,
            'perStatus'     => $perStatus,
            'perDepartemen' => $perDepartemen,
            'temuans'       => $temuans,
            'awal'          => $awal,
            'akhir'         => $akhir,
            'tahunList'     => $tahunList,
            'queryParams'   => $this->buildQueryParams(),
            'bulanList'     => [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April',   '05' => 'Mei',       '06' => 'Juni',
                '07' => 'Juli',    '08' => 'Agustus',   '09' => 'September',
                '10' => 'Oktober', '11' => 'November',  '12' => 'Desember',
            ],
        ]);
    }
}
