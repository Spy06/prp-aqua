<?php

namespace App\Livewire\BosQ;

use App\Models\BosqElemenQfs;
use Livewire\Component;
use Livewire\WithPagination;

class MasterElemenQfs extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public ?int $elemenId = null;
    public string $nama_elemen = '';
    public string $deskripsi = '';
    public bool $isEditing = false;

    public function updatingSearch(): void
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
        $elemen = BosqElemenQfs::findOrFail($id);
        $this->elemenId = $elemen->id;
        $this->nama_elemen = $elemen->nama_elemen;
        $this->deskripsi = $elemen->deskripsi ?? '';
        $this->isEditing = true;
    }

    public function save(): void
    {
        $this->validate([
            'nama_elemen' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
        ], [
            'nama_elemen.required' => 'Nama Elemen QFS wajib diisi.',
        ]);

        if ($this->isEditing && $this->elemenId) {
            $elemen = BosqElemenQfs::findOrFail($this->elemenId);
            $elemen->update([
                'nama_elemen' => $this->nama_elemen,
                'deskripsi'   => $this->deskripsi ?: null,
            ]);
            session()->flash('success', 'Master Elemen QFS berhasil diperbarui!');
        } else {
            BosqElemenQfs::create([
                'nama_elemen' => $this->nama_elemen,
                'deskripsi'   => $this->deskripsi ?: null,
            ]);
            session()->flash('success', 'Master Elemen QFS baru berhasil ditambahkan!');
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $elemen = BosqElemenQfs::findOrFail($id);
        $elemen->delete();
        session()->flash('success', 'Master Elemen QFS berhasil dihapus!');
    }

    public function resetForm(): void
    {
        $this->elemenId = null;
        $this->nama_elemen = '';
        $this->deskripsi = '';
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $elemens = BosqElemenQfs::where('nama_elemen', 'like', '%' . $this->search . '%')
            ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
            ->orderBy('nama_elemen')
            ->paginate(10);

        return view('livewire.bosq.master-elemen-qfs', [
            'elemens' => $elemens,
        ])->layout('layouts.bosq', ['title' => 'Master Elemen QFS — BOS\'Q']);
    }
}
