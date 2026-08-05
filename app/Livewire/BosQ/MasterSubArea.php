<?php

namespace App\Livewire\BosQ;

use App\Models\BosqSubArea;
use App\Models\Departemen;
use Livewire\Component;
use Livewire\WithPagination;

class MasterSubArea extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public string $filterDepartemenId = '';

    // Form Tambah / Edit Sub Area
    public ?int $subAreaId = null;
    public ?int $departemen_id = null;
    public string $nama_sub_area = '';
    public bool $showSubAreaForm = false;

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

    public function openCreateSubArea(): void
    {
        $this->resetForm();
        $this->departemen_id = $this->filterDepartemenId ? (int) $this->filterDepartemenId : null;
        $this->showSubAreaForm = true;
    }

    public function editSubArea(int $id): void
    {
        $sa = BosqSubArea::findOrFail($id);
        $this->subAreaId = $sa->id;
        $this->departemen_id = $sa->departemen_id;
        $this->nama_sub_area = $sa->nama_sub_area;
        $this->showSubAreaForm = true;
    }

    public function saveSubArea(): void
    {
        $this->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'nama_sub_area' => 'required|string|max:255',
        ], [
            'departemen_id.required' => 'Departemen (Area) wajib dipilih.',
            'nama_sub_area.required' => 'Nama Sub Area wajib diisi.',
        ]);

        if ($this->subAreaId) {
            $sa = BosqSubArea::findOrFail($this->subAreaId);
            $sa->update([
                'departemen_id' => $this->departemen_id,
                'nama_sub_area' => $this->nama_sub_area,
            ]);
            session()->flash('success', "Sub Area '{$this->nama_sub_area}' berhasil diperbarui.");
        } else {
            BosqSubArea::create([
                'departemen_id' => $this->departemen_id,
                'nama_sub_area' => $this->nama_sub_area,
            ]);
            session()->flash('success', "Sub Area baru '{$this->nama_sub_area}' berhasil ditambahkan.");
        }

        $this->resetForm();
    }

    public function deleteSubArea(int $id): void
    {
        $sa = BosqSubArea::findOrFail($id);
        $nama = $sa->nama_sub_area;
        $sa->delete();
        session()->flash('success', "Sub Area '{$nama}' berhasil dihapus.");
    }

    public function resetForm(): void
    {
        $this->subAreaId = null;
        $this->departemen_id = null;
        $this->nama_sub_area = '';
        $this->showSubAreaForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = BosqSubArea::with('departemen');

        if ($this->filterDepartemenId !== '') {
            $query->where('departemen_id', $this->filterDepartemenId);
        }

        if ($this->search !== '') {
            $query->where('nama_sub_area', 'like', '%' . $this->search . '%');
        }

        $subAreas = $query->orderBy('nama_sub_area')->paginate(15);
        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('livewire.bosq.master-sub-area', [
            'subAreas'    => $subAreas,
            'departemens' => $departemens,
        ])->layout('layouts.bosq', ['title' => 'Master Sub Area — BOS\'Q']);
    }
}
