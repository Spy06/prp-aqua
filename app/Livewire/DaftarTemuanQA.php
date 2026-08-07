<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\SubArea;
use App\Models\Temuan;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarTemuanQA extends Component
{
    use WithPagination;

    // Filter Periode
    public string $filter_type = 'bulan'; // 'bulan', 'tahun', 'custom'
    public int $bulan;
    public int $tahun;
    public string $tgl_mulai = '';
    public string $tgl_selesai = '';

    // Filter Tabel Temuan
    public string $filterDepartemen = '';
    public array $filterSubAreaNames = [];
    public string $filterStatus = '';
    public string $search = '';

    public function mount(): void
    {
        $this->bulan = (int) now()->month;
        $this->tahun = (int) now()->year;
        $this->tgl_mulai = Carbon::now()->startOfMonth()->toDateString();
        $this->tgl_selesai = Carbon::now()->toDateString();
    }

    public function updatingFilterType(): void { $this->resetPage(); }
    public function updatingBulan(): void { $this->resetPage(); }
    public function updatingTahun(): void { $this->resetPage(); }
    public function updatingFilterDepartemen(): void
    {
        $this->filterSubAreaNames = [];
        $this->resetPage();
    }
    public function updatingFilterSubAreaNames(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

    public function selectAllSubAreas(): void
    {
        $allNames = $this->filterDepartemen
            ? SubArea::where('departemen_id', $this->filterDepartemen)->pluck('nama_sub_area')->toArray()
            : SubArea::pluck('nama_sub_area')->toArray();

        if (count(array_filter($this->filterSubAreaNames)) >= count($allNames)) {
            $this->filterSubAreaNames = [];
        } else {
            $this->filterSubAreaNames = array_values(array_unique($allNames));
        }
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

        $query = Temuan::with(['departemen', 'pic'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir])
            ->orderBy('tanggal_temuan', 'desc');

        if ($this->filterDepartemen) {
            $query->where('departemen_id', $this->filterDepartemen);
        }

        $activeSubAreas = array_filter($this->filterSubAreaNames);
        if (!empty($activeSubAreas)) {
            $query->whereIn('sub_area', $activeSubAreas);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('detail_sub_area', 'like', "%{$this->search}%")
                  ->orWhere('sub_area', 'like', "%{$this->search}%")
                  ->orWhereHas('pelapor', fn($pq) => $pq->where('name', 'like', "%{$this->search}%")->orWhere('nik', 'like', "%{$this->search}%"))
                  ->orWhereHas('pic', fn($picq) => $picq->where('name', 'like', "%{$this->search}%")->orWhere('nik', 'like', "%{$this->search}%"));
            });
        }

        $departemens = Departemen::orderBy('nama_departemen')->get();
        $subAreas = $this->filterDepartemen
            ? SubArea::where('departemen_id', $this->filterDepartemen)->orderBy('nama_sub_area')->get()
            : SubArea::orderBy('nama_sub_area')->get();

        $temuans = $query->paginate(15);

        return view('livewire.daftar-temuan-q-a', [
            'filterLabel' => $filter['label'],
            'temuans'     => $temuans,
            'departemens' => $departemens,
            'subAreas'    => $subAreas,
        ]);
    }
}
