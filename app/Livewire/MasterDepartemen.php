<?php

namespace App\Livewire;

use App\Models\Departemen;
use Livewire\Component;
use Livewire\WithPagination;

class MasterDepartemen extends Component
{
    use WithPagination;

    public string $nama_departemen = '';
    public bool   $showForm        = false;
    public ?int   $editingId       = null;

    protected function rules(): array
    {
        return [
            'nama_departemen' => 'required|string|max:255',
        ];
    }

    public function resetForm(): void
    {
        $this->nama_departemen = '';
        $this->editingId       = null;
        $this->showForm        = false;
        $this->resetValidation();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $dept = Departemen::findOrFail($id);
        $this->editingId       = $dept->id;
        $this->nama_departemen = $dept->nama_departemen;
        $this->showForm        = true;
    }

    public function simpan(): void
    {
        $this->validate();

        if ($this->editingId) {
            Departemen::where('id', $this->editingId)->update([
                'nama_departemen' => $this->nama_departemen,
            ]);
            session()->flash('success', "Departemen berhasil diperbarui.");
        } else {
            Departemen::create(['nama_departemen' => $this->nama_departemen]);
            session()->flash('success', "Departemen '{$this->nama_departemen}' berhasil ditambahkan.");
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function hapus(int $id): void
    {
        $dept = Departemen::find($id);
        if (!$dept) return;

        // Cek apakah ada karyawan/temuan yang menggunakan departemen ini
        if ($dept->karyawans()->count() > 0 || $dept->temuans()->count() > 0) {
            session()->flash('error', "Departemen tidak bisa dihapus karena masih ada karyawan atau temuan yang terkait.");
            return;
        }

        $nama = $dept->nama_departemen;
        $dept->delete();
        session()->flash('success', "Departemen '{$nama}' berhasil dihapus.");
    }

    public function render()
    {
        $departemens = Departemen::withCount(['karyawans', 'temuans'])
            ->orderBy('nama_departemen')
            ->paginate(15);

        return view('livewire.master-departemen', [
            'departemens' => $departemens,
        ]);
    }
}
