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

class DaftarObservasiQA extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

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
    public function updatingFilterDepartemenId(): void { $this->resetPage(); }
    public function updatingFilterSubAreaId(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterTingkatResiko(): void { $this->resetPage(); }
    public function updatingFilterDampakTemuan(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

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

        $query = BosqTemuan::with(['departemen', 'subArea', 'elemenQfs', 'pelapor', 'auditee', 'tindakLanjut'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir]);

        if ($this->filter_departemen_id !== '') {
            $query->where('departemen_id', $this->filter_departemen_id);
        }

        if ($this->filter_sub_area_id !== '') {
            $query->where('sub_area_id', $this->filter_sub_area_id);
        }

        if ($this->filter_status !== '') {
            if ($this->filter_status === 'open') {
                $query->whereIn('status', ['open', 'in_progress', 'closed_pending_qa']);
            } else {
                $query->whereIn('status', ['closed', 'closed_acc']);
            }
        }

        if ($this->filter_tingkat_resiko !== '') {
            $query->where('tingkat_resiko', $this->filter_tingkat_resiko);
        }

        if ($this->filter_dampak_temuan !== '') {
            $query->where('dampak_temuan', $this->filter_dampak_temuan);
        }

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('detail_sub_area', 'like', "%{$this->search}%")
                  ->orWhere('deskripsi_temuan', 'like', "%{$this->search}%")
                  ->orWhereHas('pelapor', fn($pq) => $pq->where('name', 'like', "%{$this->search}%")->orWhere('nik', 'like', "%{$this->search}%"));
            });
        }

        $temuans = $query->orderBy('tanggal_temuan', 'desc')->orderBy('id', 'desc')->paginate(15);

        $departemens = Departemen::orderBy('nama_departemen')->get();
        $subAreas = $this->filter_departemen_id
            ? BosqSubArea::where('departemen_id', $this->filter_departemen_id)->orWhereNull('departemen_id')->orderBy('nama_sub_area')->get()
            : BosqSubArea::orderBy('nama_sub_area')->get();

        return view('livewire.bosq.daftar-observasi-q-a', [
            'filterLabel' => $filter['label'],
            'temuans'     => $temuans,
            'departemens' => $departemens,
            'subAreas'    => $subAreas,
        ])->layout('layouts.bosq', ['title' => 'Daftar Observasi — BOS\'Q']);
    }
}
