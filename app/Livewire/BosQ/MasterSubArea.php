<?php

namespace App\Livewire\BosQ;

use App\Models\BosqSubArea;
use App\Models\Departemen;
use Livewire\Component;
use Livewire\WithPagination;

class MasterSubArea extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $filterDepartemenId = '';

    public ?int $subAreaId = null;
    public ?int $departemen_id = null;
    public string $nama_sub_area = '';
    public bool $isEditing = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDepartemenId(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
    }

    public function edit(int $id): void
    {
        $sa = BosqSubArea::findOrFail($id);
        $this->subAreaId = $sa->id;
        $this->departemen_id = $sa->departemen_id;
        $this->nama_sub_area = $sa->nama_sub_area;
        $this->isEditing = true;
    }

    public function save(): void
    {
        $this->validate([
            'departemen_id'  => 'nullable|exists:departemen,id',
            'nama_sub_area'  => 'required|string|max:255',
        ], [
            'nama_sub_area.required' => 'Nama Sub Area wajib diisi.',
        ]);

        if ($this->isEditing && $this->subAreaId) {
            $sa = BosqSubArea::findOrFail($this->subAreaId);
            $sa->update([
                'departemen_id' => $this->departemen_id ?: null,
                'nama_sub_area' => $this->nama_sub_area,
            ]);
            session()->flash('success', 'Master Sub Area berhasil diperbarui!');
        } else {
            BosqSubArea::create([
                'departemen_id' => $this->departemen_id ?: null,
                'nama_sub_area' => $this->nama_sub_area,
            ]);
            session()->flash('success', 'Master Sub Area baru berhasil ditambahkan!');
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $sa = BosqSubArea::findOrFail($id);
        $sa->delete();
        session()->flash('success', 'Master Sub Area berhasil dihapus!');
    }

    public function resetForm(): void
    {
        $this->subAreaId = null;
        $this->departemen_id = null;
        $this->nama_sub_area = '';
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = BosqSubArea::with('departemen')
            ->where('nama_sub_area', 'like', '%' . $this->search . '%');

        if ($this->filterDepartemenId !== '') {
            $query->where('departemen_id', $this->filterDepartemenId);
        }

        $subAreas = $query->orderByRaw("CASE WHEN nama_sub_area = 'Others' THEN 1 ELSE 0 END")
            ->orderBy('nama_sub_area')
            ->paginate(12);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('livewire.bosq.master-sub-area', [
            'subAreas'    => $subAreas,
            'departemens' => $departemens,
        ])->layout('layouts.bosq', ['title' => 'Master Sub Area — BOS\'Q']);
    }
}
