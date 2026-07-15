<?php

namespace App\Livewire;

use App\Models\KlausulPrp;
use Livewire\Component;
use Livewire\WithPagination;

class MasterKlausul extends Component
{
    use WithPagination;

    public string $kode_klausul = '';
    public string $nama_klausul = '';
    public bool   $showForm     = false;
    public ?int   $editingId    = null;

    protected function rules(): array
    {
        return [
            'kode_klausul' => 'required|string|max:50',
            'nama_klausul' => 'required|string|max:500',
        ];
    }

    public function resetForm(): void
    {
        $this->kode_klausul = '';
        $this->nama_klausul = '';
        $this->editingId    = null;
        $this->showForm     = false;
        $this->resetValidation();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $k = KlausulPrp::findOrFail($id);
        $this->editingId    = $k->id;
        $this->kode_klausul = $k->kode_klausul;
        $this->nama_klausul = $k->nama_klausul;
        $this->showForm     = true;
    }

    public function simpan(): void
    {
        $this->validate();

        if ($this->editingId) {
            KlausulPrp::where('id', $this->editingId)->update([
                'kode_klausul' => $this->kode_klausul,
                'nama_klausul' => $this->nama_klausul,
            ]);
            session()->flash('success', "Klausul {$this->kode_klausul} berhasil diperbarui.");
        } else {
            KlausulPrp::create([
                'kode_klausul' => $this->kode_klausul,
                'nama_klausul' => $this->nama_klausul,
            ]);
            session()->flash('success', "Klausul {$this->kode_klausul} berhasil ditambahkan.");
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function hapus(int $id): void
    {
        $k = KlausulPrp::with('temuans')->find($id);
        if (!$k) return;

        if ($k->temuans()->count() > 0) {
            session()->flash('error', "Klausul tidak bisa dihapus karena sudah digunakan di laporan temuan.");
            return;
        }

        $kode = $k->kode_klausul;
        $k->delete();
        session()->flash('success', "Klausul '{$kode}' berhasil dihapus.");
    }

    public function render()
    {
        $klausuls = KlausulPrp::withCount('temuans')
            ->orderBy('kode_klausul')
            ->paginate(20);

        return view('livewire.master-klausul', [
            'klausuls' => $klausuls,
        ]);
    }
}
