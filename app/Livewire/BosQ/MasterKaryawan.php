<?php

namespace App\Livewire\BosQ;

use App\Models\Departemen;
use App\Models\Karyawan;
use Livewire\Component;
use Livewire\WithPagination;

class MasterKaryawan extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public string $filterDepartemenId = '';

    // Autocomplete Search & Selection for Adding Divisi Manajemen Member
    public string  $searchKaryawan = '';
    public ?string $selectedNik    = null;
    public string  $selectedNama   = '';
    public string  $selectedDept   = '';
    public bool    $status_aktif   = true;
    public array   $recommendations= [];

    public bool    $showForm       = false;
    public ?string $editingNik     = null;

    public function updatedSearchKaryawan(): void
    {
        $query = trim($this->searchKaryawan);

        if (strlen($query) >= 1) {
            $this->recommendations = Karyawan::with('departemen')
                ->where('is_anggota_divisi_manajemen', false)
                ->where(function ($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%")
                      ->orWhere('nik', 'like', "%{$query}%");
                })
                ->orderBy('nama')
                ->take(8)
                ->get()
                ->toArray();
        } else {
            $this->recommendations = [];
        }
    }

    public function selectKaryawan(string $nik): void
    {
        $k = Karyawan::with('departemen')->find($nik);
        if ($k) {
            $this->selectedNik    = $k->nik;
            $this->selectedNama   = $k->nama;
            $this->selectedDept   = $k->departemen->nama_departemen ?? '-';
            $this->status_aktif   = true;
            $this->searchKaryawan = "{$k->nama} (NIK: {$k->nik})";
            $this->recommendations = [];
        }
    }

    public function clearSelectedKaryawan(): void
    {
        $this->selectedNik     = null;
        $this->selectedNama    = '';
        $this->selectedDept    = '';
        $this->searchKaryawan  = '';
        $this->recommendations = [];
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(string $nik): void
    {
        $k = Karyawan::with('departemen')->where('is_anggota_divisi_manajemen', true)->findOrFail($nik);

        $this->editingNik    = $k->nik;
        $this->selectedNik   = $k->nik;
        $this->selectedNama  = $k->nama;
        $this->selectedDept  = $k->departemen->nama_departemen ?? '-';
        $this->status_aktif  = $k->status_aktif;
        $this->showForm      = true;
    }

    public function save(): void
    {
        if ($this->editingNik) {
            $karyawan = Karyawan::where('is_anggota_divisi_manajemen', true)->findOrFail($this->editingNik);
            $karyawan->update(['status_aktif' => $this->status_aktif]);
            session()->flash('success', "Status anggota divisi manajemen '{$karyawan->nama}' berhasil diperbarui.");
        } else {
            if (!$this->selectedNik) {
                $this->addError('searchKaryawan', 'Pilih nama karyawan dari daftar rekomendasi hasil pencarian.');
                return;
            }

            $karyawan = Karyawan::findOrFail($this->selectedNik);
            $karyawan->update([
                'is_anggota_divisi_manajemen' => true,
                'status_aktif'                => true,
            ]);

            session()->flash('success', "Karyawan '{$karyawan->nama}' ({$karyawan->nik}) berhasil ditambahkan ke Divisi Manajemen BOS'Q.");
        }

        $this->resetForm();
    }

    public function toggleDivisiManajemen(string $nik): void
    {
        $karyawan = Karyawan::where('is_anggota_divisi_manajemen', true)->findOrFail($nik);
        $karyawan->update([
            'is_anggota_divisi_manajemen' => false,
            'status_aktif'                => true,
        ]);
        session()->flash('success', "{$karyawan->nama} ({$karyawan->nik}) telah dihapus dari Divisi Manajemen BOS'Q.");
    }

    public function toggleStatusAktif(string $nik): void
    {
        $karyawan = Karyawan::where('is_anggota_divisi_manajemen', true)->findOrFail($nik);
        $karyawan->update(['status_aktif' => !$karyawan->status_aktif]);
        $statusStr = $karyawan->status_aktif ? 'diaktifkan kembali' : 'dinonaktifkan';
        session()->flash('success', "Status anggota divisi manajemen {$karyawan->nama} ({$karyawan->nik}) berhasil {$statusStr}.");
    }

    public function resetForm(): void
    {
        $this->clearSelectedKaryawan();
        $this->showForm   = false;
        $this->editingNik = null;
        $this->resetValidation();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDepartemenId(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Karyawan::with(['departemen', 'user'])
            ->where('is_anggota_divisi_manajemen', true)
            ->where('nama', 'not like', '%super administrator%')
            ->whereDoesntHave('user', function ($q) {
                $q->where('role', 'superadmin');
            });

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterDepartemenId !== '') {
            $query->where('departemen_id', $this->filterDepartemenId);
        }

        $karyawans = $query->orderBy('nama')->paginate(12);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        $totalManajemen = Karyawan::where('nama', 'not like', '%super administrator%')->where('is_anggota_divisi_manajemen', true)->count();

        return view('livewire.bosq.master-karyawan', [
            'karyawans'      => $karyawans,
            'departemens'    => $departemens,
            'totalManajemen' => $totalManajemen,
        ])->layout('layouts.bosq', ['title' => 'Divisi Manajemen — BOS\'Q']);
    }
}
