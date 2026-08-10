<?php

namespace App\Livewire\BosQ;

use App\Models\BosqElemenQfs;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Elemen QFS — BOS\'Q')]
class MasterElemenQfs extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public ?int $customId = null;
    public string $nama_elemen = '';
    public string $deskripsi = '';
    public bool $showForm = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $elemen = BosqElemenQfs::findOrFail($id);
        $this->editingId = $elemen->id;
        $this->customId = $elemen->id;
        $this->nama_elemen = $elemen->nama_elemen;
        $this->deskripsi = $elemen->deskripsi ?? '';
        $this->showForm = true;
        $this->resetValidation();
    }

    public function simpan(): void
    {
        if ($this->editingId) {
            $this->validate([
                'customId'    => 'required|integer|min:1|unique:bosq_elemen_qfs,id,' . $this->editingId,
                'nama_elemen' => 'required|string|max:255',
                'deskripsi'   => 'nullable|string',
            ], [
                'customId.required'    => 'ID Elemen QFS wajib diisi.',
                'customId.unique'      => 'ID Elemen QFS tersebut sudah digunakan.',
                'nama_elemen.required' => 'Nama Elemen QFS wajib diisi.',
            ]);

            $elemen = BosqElemenQfs::findOrFail($this->editingId);
            $elemen->update([
                'id'          => $this->customId,
                'nama_elemen' => $this->nama_elemen,
                'deskripsi'   => $this->deskripsi ?: null,
            ]);
            session()->flash('success', "Elemen QFS #{$this->customId} '{$this->nama_elemen}' berhasil diperbarui.");
        } else {
            $rules = [
                'nama_elemen' => 'required|string|max:255',
                'deskripsi'   => 'nullable|string',
            ];
            if ($this->customId) {
                $rules['customId'] = 'integer|min:1|unique:bosq_elemen_qfs,id';
            }
            $this->validate($rules, [
                'customId.unique'      => 'ID Elemen QFS tersebut sudah digunakan.',
                'nama_elemen.required' => 'Nama Elemen QFS wajib diisi.',
            ]);

            $data = [
                'nama_elemen' => $this->nama_elemen,
                'deskripsi'   => $this->deskripsi ?: null,
            ];
            if ($this->customId) {
                $data['id'] = $this->customId;
            }

            $created = BosqElemenQfs::create($data);
            session()->flash('success', "Elemen QFS baru #{$created->id} '{$this->nama_elemen}' berhasil ditambahkan.");
        }

        $this->resetForm();
    }

    public function hapus(int $id): void
    {
        $elemen = BosqElemenQfs::withCount('temuans')->findOrFail($id);

        if ($elemen->temuans_count > 0) {
            session()->flash('error', "Elemen QFS '{$elemen->nama_elemen}' tidak dapat dihapus karena sedang digunakan di {$elemen->temuans_count} data temuan/observasi.");
            return;
        }

        $nama = $elemen->nama_elemen;
        $elemen->delete();
        session()->flash('success', "Elemen QFS '{$nama}' berhasil dihapus.");
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->customId = null;
        $this->nama_elemen = '';
        $this->deskripsi = '';
        $this->showForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        $elemens = BosqElemenQfs::withCount('temuans')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('id', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_elemen', 'like', '%' . $this->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.bosq.master-elemen-qfs', [
            'elemens' => $elemens,
        ])->layout('layouts.bosq', ['title' => 'Master Elemen QFS — BOS\'Q']);
    }
}
