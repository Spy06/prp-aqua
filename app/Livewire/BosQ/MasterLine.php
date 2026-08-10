<?php

namespace App\Livewire\BosQ;

use App\Models\BosqSubArea;
use App\Models\Departemen;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('PIC Sub Area — BOS\'Q')]
class MasterLine extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public string $filterDepartemenId = '';

    // Modal / Drawer Kelola PIC Sub Area
    public ?int $managingSubAreaId = null;
    public string $picSearch = '';
    public array $picResults = [];

    public function mount(): void
    {
        $firstDept = Departemen::orderBy('nama_departemen')->first();
        if ($firstDept) {
            $this->filterDepartemenId = (string) $firstDept->id;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDepartemenId(): void
    {
        $this->resetPage();
    }

    public function openManagePics(int $subAreaId): void
    {
        $this->managingSubAreaId = $subAreaId;
        $this->picSearch = '';
        $this->picResults = [];
    }

    public function closeManagePics(): void
    {
        $this->managingSubAreaId = null;
        $this->picSearch = '';
        $this->picResults = [];
    }

    public function updatedPicSearch(): void
    {
        $query = trim($this->picSearch);
        if (strlen($query) >= 1) {
            $this->picResults = User::with('karyawan.departemen')
                ->where('name', 'not like', '%super administrator%')
                ->whereDoesntHave('karyawan', fn($q) => $q->where('nama', 'like', '%super administrator%'))
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('nik', 'like', '%' . $query . '%');
                })
                ->orderBy('name')
                ->take(10)
                ->get()
                ->toArray();
        } else {
            $this->picResults = [];
        }
    }

    public function addPic(int $userId): void
    {
        if (!$this->managingSubAreaId) return;

        $subArea = BosqSubArea::findOrFail($this->managingSubAreaId);
        $user = User::findOrFail($userId);

        if (!$subArea->pics()->where('user_id', $userId)->exists()) {
            $subArea->pics()->attach($userId);
            session()->flash('success', "PIC '{$user->name}' berhasil ditambahkan ke Sub Area {$subArea->nama_sub_area}.");
        } else {
            session()->flash('info', "'{$user->name}' sudah menjadi PIC di Sub Area ini.");
        }

        $this->picSearch = '';
        $this->picResults = [];
    }

    public function removePic(int $subAreaId, int $userId): void
    {
        $subArea = BosqSubArea::findOrFail($subAreaId);
        $user = User::find($userId);
        $subArea->pics()->detach($userId);

        $name = $user ? $user->name : 'PIC';
        session()->flash('success', "{$name} berhasil dihapus dari PIC Sub Area {$subArea->nama_sub_area}.");
    }

    public function render()
    {
        $query = BosqSubArea::with(['departemen', 'pics.karyawan.departemen']);

        if ($this->filterDepartemenId !== '') {
            $query->where('departemen_id', $this->filterDepartemenId);
        }

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('nama_sub_area', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pics', function ($pq) {
                      $pq->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('nik', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $subAreas = $query->orderBy('nama_sub_area')->paginate(12);
        $departemens = Departemen::orderBy('nama_departemen')->get();

        $managingSubArea = $this->managingSubAreaId
            ? BosqSubArea::with('pics.karyawan.departemen')->find($this->managingSubAreaId)
            : null;

        return view('livewire.bosq.master-line', [
            'subAreas'        => $subAreas,
            'departemens'     => $departemens,
            'managingSubArea' => $managingSubArea,
        ])->layout('layouts.bosq', ['title' => 'Master Line & PIC — BOS\'Q']);
    }
}
