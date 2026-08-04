<?php

namespace App\Livewire\BosQ;

use App\Models\Departemen;
use App\Models\Karyawan;
use Livewire\Component;
use Livewire\WithPagination;

class MasterKaryawan extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $filterDepartemenId = '';
    public string $filterDivisiManajemen = '';

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

    public function toggleDivisiManajemen(string $nik): void
    {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();
        $karyawan->is_anggota_divisi_manajemen = !$karyawan->is_anggota_divisi_manajemen;
        $karyawan->save();

        $statusStr = $karyawan->is_anggota_divisi_manajemen ? 'ditambahkan sebagai' : 'dihapus dari';
        session()->flash('success', "{$karyawan->nama} ({$karyawan->nik}) berhasil {$statusStr} Anggota Divisi Manajemen.");
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
        ])->layout('layouts.bosq', ['title' => 'Divisi Manajemen — BOS\'Q']);
    }
}
