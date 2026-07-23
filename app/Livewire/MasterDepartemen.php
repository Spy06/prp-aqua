<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\SubArea;
use Livewire\Component;
use Livewire\WithPagination;

class MasterDepartemen extends Component
{
    use WithPagination;

    public string $nama_departemen = '';
    public bool   $showForm        = false;
    public ?int   $editingId       = null;

    // ── Sub Area Management Properties ──
    public ?int   $selectedDeptIdForSubArea = null;
    public string $newSubAreaName          = '';
    public ?int   $editingSubAreaId        = null;
    public string $editingSubAreaName      = '';

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
            $dept = Departemen::create(['nama_departemen' => $this->nama_departemen]);
            // Otomatis buat opsi 'Others' untuk departemen baru
            SubArea::create([
                'departemen_id' => $dept->id,
                'nama_sub_area' => 'Others',
            ]);
            session()->flash('success', "Departemen '{$this->nama_departemen}' berhasil ditambahkan (dengan opsi 'Others').");
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
        // Hapus sub areas terkait
        $dept->subAreas()->delete();
        $dept->delete();
        session()->flash('success', "Departemen '{$nama}' berhasil dihapus.");
    }

    // ── SUB AREA ACTIONS ──

    public function openSubAreaModal(int $deptId): void
    {
        $this->selectedDeptIdForSubArea = $deptId;
        $this->newSubAreaName          = '';
        $this->editingSubAreaId        = null;
        $this->editingSubAreaName      = '';
        $this->resetValidation();
    }

    public function closeSubAreaModal(): void
    {
        $this->selectedDeptIdForSubArea = null;
        $this->newSubAreaName          = '';
        $this->editingSubAreaId        = null;
        $this->editingSubAreaName      = '';
    }

    public function tambahSubArea(): void
    {
        $this->validate([
            'newSubAreaName' => 'required|string|max:255',
        ], [
            'newSubAreaName.required' => 'Nama sub area wajib diisi.',
        ]);

        $nama = trim($this->newSubAreaName);

        // Cek duplikasi di departemen ini
        $exists = SubArea::where('departemen_id', $this->selectedDeptIdForSubArea)
            ->where('nama_sub_area', $nama)
            ->exists();

        if ($exists) {
            $this->addError('newSubAreaName', 'Sub area ini sudah ada di departemen.');
            return;
        }

        SubArea::create([
            'departemen_id' => $this->selectedDeptIdForSubArea,
            'nama_sub_area' => $nama,
        ]);

        $this->newSubAreaName = '';
        session()->flash('subarea_success', "Sub area '{$nama}' berhasil ditambahkan.");
    }

    public function editSubArea(int $subAreaId): void
    {
        $sa = SubArea::findOrFail($subAreaId);
        $this->editingSubAreaId   = $sa->id;
        $this->editingSubAreaName = $sa->nama_sub_area;
    }

    public function cancelEditSubArea(): void
    {
        $this->editingSubAreaId   = null;
        $this->editingSubAreaName = '';
    }

    public function simpanEditSubArea(): void
    {
        $this->validate([
            'editingSubAreaName' => 'required|string|max:255',
        ], [
            'editingSubAreaName.required' => 'Nama sub area tidak boleh kosong.',
        ]);

        $nama = trim($this->editingSubAreaName);

        // Cek duplikasi selain id yang sedang diedit
        $exists = SubArea::where('departemen_id', $this->selectedDeptIdForSubArea)
            ->where('nama_sub_area', $nama)
            ->where('id', '!=', $this->editingSubAreaId)
            ->exists();

        if ($exists) {
            $this->addError('editingSubAreaName', 'Nama sub area ini sudah digunakan di departemen ini.');
            return;
        }

        SubArea::where('id', $this->editingSubAreaId)->update([
            'nama_sub_area' => $nama,
        ]);

        $this->editingSubAreaId   = null;
        $this->editingSubAreaName = '';
        session()->flash('subarea_success', "Sub area berhasil diperbarui.");
    }

    public function hapusSubArea(int $subAreaId): void
    {
        $sa = SubArea::find($subAreaId);
        if (!$sa) return;

        $nama = $sa->nama_sub_area;
        $sa->delete();
        session()->flash('subarea_success', "Sub area '{$nama}' berhasil dihapus.");
    }

    public function tambahOpsiOthers(): void
    {
        if (!$this->selectedDeptIdForSubArea) return;

        $exists = SubArea::where('departemen_id', $this->selectedDeptIdForSubArea)
            ->where('nama_sub_area', 'Others')
            ->exists();

        if (!$exists) {
            SubArea::create([
                'departemen_id' => $this->selectedDeptIdForSubArea,
                'nama_sub_area' => 'Others',
            ]);
            session()->flash('subarea_success', "Opsi 'Others' berhasil ditambahkan.");
        }
    }

    public function render()
    {
        $departemens = Departemen::withCount(['karyawans', 'temuans', 'subAreas'])
            ->orderBy('nama_departemen')
            ->paginate(15);

        $selectedDept = null;
        $subAreasList = collect();

        if ($this->selectedDeptIdForSubArea) {
            $selectedDept = Departemen::find($this->selectedDeptIdForSubArea);
            if ($selectedDept) {
                $subAreasList = SubArea::where('departemen_id', $selectedDept->id)
                    ->orderByRaw("CASE WHEN nama_sub_area = 'Others' THEN 1 ELSE 0 END")
                    ->orderBy('nama_sub_area')
                    ->get();
            }
        }

        return view('livewire.master-departemen', [
            'departemens'  => $departemens,
            'selectedDept' => $selectedDept,
            'subAreasList' => $subAreasList,
        ]);
    }
}
