<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MasterKaryawan extends Component
{
    use WithPagination;

    // Autocomplete Search & Selection for Adding PIC
    public string  $searchKaryawan = '';
    public ?string $selectedNik    = null;
    public string  $selectedNama   = '';
    public string  $selectedDept   = '';
    public string  $email          = '';
    public bool    $status_aktif   = true;
    public array   $recommendations= [];

    // Edit PIC State
    public ?string $editingNik     = null;

    // UI state & filters
    public bool    $showForm         = false;
    public string  $search           = '';
    public string  $filterDepartemen = '';

    public function updatedSearchKaryawan(): void
    {
        $query = trim($this->searchKaryawan);

        if (strlen($query) >= 1) {
            $this->recommendations = Karyawan::with('departemen', 'user')
                ->where('is_pic', false)
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
        $k = Karyawan::with('departemen', 'user')->find($nik);
        if ($k) {
            $this->selectedNik    = $k->nik;
            $this->selectedNama   = $k->nama;
            $this->selectedDept   = $k->departemen->nama_departemen ?? '-';
            $this->email          = $k->user?->email ?? '';
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
        $this->email           = '';
        $this->searchKaryawan  = '';
        $this->recommendations = [];
    }

    public function resetForm(): void
    {
        $this->clearSelectedKaryawan();
        $this->editingNik   = null;
        $this->showForm     = false;
        $this->resetValidation();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(string $nik): void
    {
        $k = Karyawan::with('departemen', 'user')->where('is_pic', true)->findOrFail($nik);

        $this->editingNik    = $k->nik;
        $this->selectedNik   = $k->nik;
        $this->selectedNama  = $k->nama;
        $this->selectedDept  = $k->departemen->nama_departemen ?? '-';
        $this->email         = $k->user?->email ?? '';
        $this->status_aktif  = $k->status_aktif;
        $this->showForm      = true;
    }

    public function simpan(): void
    {
        if ($this->editingNik) {
            $karyawan = Karyawan::with('user')->where('is_pic', true)->findOrFail($this->editingNik);
            
            $karyawan->update([
                'status_aktif' => $this->status_aktif,
            ]);

            if ($karyawan->user) {
                $karyawan->user->update([
                    'email' => !empty($this->email) ? trim($this->email) : null,
                ]);
            }

            session()->flash('success', "Status & data PIC {$karyawan->nama} berhasil diperbarui.");
        } else {
            if (!$this->selectedNik) {
                $this->addError('searchKaryawan', 'Pilih nama karyawan dari daftar rekomendasi hasil pencarian.');
                return;
            }

            $karyawan = Karyawan::with('user')->findOrFail($this->selectedNik);
            $karyawan->update([
                'is_pic'       => true,
                'status_aktif' => true,
            ]);

            if ($karyawan->user && !empty($this->email)) {
                $karyawan->user->update(['email' => trim($this->email)]);
            }

            session()->flash('success', "Karyawan {$karyawan->nama} (NIK: {$karyawan->nik}) berhasil ditambahkan sebagai Master PIC SIVERA.");
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function toggleStatus(string $nik): void
    {
        $k = Karyawan::where('is_pic', true)->findOrFail($nik);
        $k->update(['status_aktif' => !$k->status_aktif]);
        $statusStr = $k->status_aktif ? 'diaktifkan kembali' : 'dinonaktifkan';
        session()->flash('success', "Status PIC {$k->nama} berhasil {$statusStr}.");
    }

    public function hapusPic(string $nik): void
    {
        $k = Karyawan::where('is_pic', true)->findOrFail($nik);
        $k->update([
            'is_pic'       => false,
            'status_aktif' => true,
        ]);
        session()->flash('success', "Karyawan {$k->nama} berhasil dihapus dari Master PIC SIVERA (akses kembali ke Karyawan biasa).");
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDepartemen(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $pics = Karyawan::with(['departemen', 'user'])
            ->where('is_pic', true)
            ->when($this->filterDepartemen, function ($q) {
                $q->where('departemen_id', $this->filterDepartemen);
            })
            ->when($this->search, fn ($q) => $q
                ->where(function ($sub) {
                    $sub->where('nik', 'like', "%{$this->search}%")
                        ->orWhere('nama', 'like', "%{$this->search}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('email', 'like', "%{$this->search}%"));
                })
            )
            ->orderBy('nama')
            ->paginate(15);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('livewire.master-karyawan', [
            'pics'        => $pics,
            'departemens' => $departemens,
        ]);
    }
}
