<?php

namespace App\Livewire\BosQ;

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class MasterKaryawan extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public string $filterDepartemenId = '';
    public string $filterDivisiManajemen = '';

    // Form Tambah / Edit Karyawan
    public bool $showForm = false;
    public bool $isEditing = false;
    public ?string $editNik = null;

    public string $nik = '';
    public string $nama = '';
    public ?int $departemen_id = null;
    public bool $is_anggota_divisi_manajemen = true;
    public bool $status_aktif = true;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDepartemenId(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDivisiManajemen(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->departemen_id = $this->filterDepartemenId ? (int) $this->filterDepartemenId : null;
        $this->showForm = true;
        $this->isEditing = false;
    }

    public function edit(string $nik): void
    {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();
        $this->editNik = $karyawan->nik;
        $this->nik = $karyawan->nik;
        $this->nama = $karyawan->nama;
        $this->departemen_id = $karyawan->departemen_id;
        $this->is_anggota_divisi_manajemen = (bool) $karyawan->is_anggota_divisi_manajemen;
        $this->status_aktif = (bool) $karyawan->status_aktif;
        $this->showForm = true;
        $this->isEditing = true;
    }

    public function save(): void
    {
        $uniqueNikRule = $this->isEditing && $this->editNik
            ? 'required|string|max:50|unique:karyawan,nik,' . $this->editNik . ',nik'
            : 'required|string|max:50|unique:karyawan,nik';

        $this->validate([
            'nik'                         => $uniqueNikRule,
            'nama'                        => 'required|string|max:255',
            'departemen_id'               => 'required|exists:departemen,id',
            'is_anggota_divisi_manajemen' => 'boolean',
            'status_aktif'                => 'boolean',
        ], [
            'nik.required'           => 'NIK karyawan wajib diisi.',
            'nik.unique'             => 'NIK ini sudah terdaftar.',
            'nama.required'          => 'Nama karyawan wajib diisi.',
            'departemen_id.required' => 'Departemen wajib dipilih.',
        ]);

        if ($this->isEditing && $this->editNik) {
            $karyawan = Karyawan::where('nik', $this->editNik)->firstOrFail();
            
            // Sync user linked to old NIK
            $linkedUser = User::where('nik', $this->editNik)->first();
            if ($linkedUser) {
                $linkedUser->update([
                    'nik'  => $this->nik,
                    'name' => $this->nama,
                ]);
            }

            $karyawan->update([
                'nik'                         => $this->nik,
                'nama'                        => $this->nama,
                'departemen_id'               => $this->departemen_id,
                'is_anggota_divisi_manajemen' => $this->is_anggota_divisi_manajemen,
                'status_aktif'                => $this->status_aktif,
            ]);

            session()->flash('success', "Data karyawan '{$this->nama}' ({$this->nik}) berhasil diperbarui.");
        } else {
            Karyawan::create([
                'nik'                         => $this->nik,
                'nama'                        => $this->nama,
                'departemen_id'               => $this->departemen_id,
                'is_anggota_divisi_manajemen' => $this->is_anggota_divisi_manajemen,
                'status_aktif'                => $this->status_aktif,
            ]);

            // Auto create user account for new employee
            User::firstOrCreate([
                'nik' => $this->nik,
            ], [
                'name'     => $this->nama,
                'password' => Hash::make($this->nik),
                'role'     => 'karyawan',
            ]);

            session()->flash('success', "Karyawan baru '{$this->nama}' ({$this->nik}) berhasil ditambahkan.");
        }

        $this->resetForm();
    }

    public function delete(string $nik): void
    {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();
        $nama = $karyawan->nama;

        // Delete linked user if any
        $linkedUser = User::where('nik', $nik)->first();
        if ($linkedUser && $linkedUser->role === 'karyawan') {
            $linkedUser->delete();
        }

        $karyawan->delete();

        session()->flash('success', "Karyawan '{$nama}' ({$nik}) telah berhasil dihapus dari sistem.");
    }

    public function toggleDivisiManajemen(string $nik): void
    {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();
        $karyawan->is_anggota_divisi_manajemen = !$karyawan->is_anggota_divisi_manajemen;
        $karyawan->save();

        $statusStr = $karyawan->is_anggota_divisi_manajemen ? 'ditambahkan sebagai' : 'dihapus dari';
        session()->flash('success', "{$karyawan->nama} ({$karyawan->nik}) berhasil {$statusStr} Anggota Divisi Manajemen.");
    }

    public function toggleStatusAktif(string $nik): void
    {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();
        $karyawan->status_aktif = !$karyawan->status_aktif;
        $karyawan->save();

        $statusStr = $karyawan->status_aktif ? 'diaktifkan kembali' : 'dinonaktifkan';
        session()->flash('success', "Status karyawan {$karyawan->nama} ({$karyawan->nik}) berhasil {$statusStr}.");
    }

    public function resetForm(): void
    {
        $this->showForm = false;
        $this->isEditing = false;
        $this->editNik = null;
        $this->nik = '';
        $this->nama = '';
        $this->departemen_id = null;
        $this->is_anggota_divisi_manajemen = true;
        $this->status_aktif = true;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Karyawan::with(['departemen', 'user'])
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

        if ($this->filterDivisiManajemen !== '') {
            $query->where('is_anggota_divisi_manajemen', $this->filterDivisiManajemen === '1');
        }

        $karyawans = $query->orderBy('nama')->paginate(12);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        $totalKaryawan = Karyawan::where('nama', 'not like', '%super administrator%')->count();
        $totalManajemen = Karyawan::where('nama', 'not like', '%super administrator%')->where('is_anggota_divisi_manajemen', true)->count();

        return view('livewire.bosq.master-karyawan', [
            'karyawans'      => $karyawans,
            'departemens'    => $departemens,
            'totalKaryawan'  => $totalKaryawan,
            'totalManajemen' => $totalManajemen,
        ])->layout('layouts.bosq', ['title' => 'Divisi Manajemen & Master Karyawan — BOS\'Q']);
    }
}
