<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\Karyawan;
use Livewire\Component;
use Livewire\WithPagination;

class MasterKaryawan extends Component
{
    use WithPagination;

    // Form fields
    public string  $nik          = '';
    public string  $nama         = '';
    public string  $departemen_id = '';
    public bool    $status_aktif  = true;

    // State UI
    public bool    $showForm      = false;
    public ?string $editingNik    = null;
    public string  $search        = '';

    protected function rules(): array
    {
        return [
            'nik'           => 'required|string|max:20' . ($this->editingNik ? '' : '|unique:karyawan,nik'),
            'nama'          => 'required|string|max:255',
            'departemen_id' => 'required|exists:departemen,id',
            'status_aktif'  => 'boolean',
        ];
    }

    protected array $messages = [
        'nik.unique'       => 'NIK ini sudah terdaftar di sistem.',
        'departemen_id.exists' => 'Departemen tidak ditemukan.',
    ];

    public function resetForm(): void
    {
        $this->nik           = '';
        $this->nama          = '';
        $this->departemen_id = '';
        $this->status_aktif  = true;
        $this->editingNik    = null;
        $this->showForm      = false;
        $this->resetValidation();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(string $nik): void
    {
        $k = Karyawan::findOrFail($nik);
        $this->editingNik    = $k->nik;
        $this->nik           = $k->nik;
        $this->nama          = $k->nama;
        $this->departemen_id = (string) $k->departemen_id;
        $this->status_aktif  = $k->status_aktif;
        $this->showForm      = true;
    }

    public function simpan(): void
    {
        $this->validate();

        if ($this->editingNik) {
            // Update (NIK tidak bisa diubah — primary key)
            Karyawan::where('nik', $this->editingNik)->update([
                'nama'          => $this->nama,
                'departemen_id' => $this->departemen_id,
                'status_aktif'  => $this->status_aktif,
            ]);
            session()->flash('success', "Karyawan {$this->nama} berhasil diperbarui.");
        } else {
            Karyawan::create([
                'nik'           => $this->nik,
                'nama'          => $this->nama,
                'departemen_id' => $this->departemen_id,
                'status_aktif'  => $this->status_aktif,
            ]);
            session()->flash('success', "Karyawan {$this->nama} (NIK: {$this->nik}) berhasil ditambahkan.");
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function toggleStatus(string $nik): void
    {
        $this->toggleAktif($nik);
    }

    public function toggleAktif(string $nik): void
    {
        $k = Karyawan::findOrFail($nik);
        $k->update(['status_aktif' => !$k->status_aktif]);
        $statusStr = $k->status_aktif ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Status karyawan {$k->nama} berhasil {$statusStr}.");
    }

    public function hapus(string $nik): void
    {
        $k = Karyawan::findOrFail($nik);
        $user = $k->user;

        if ($user) {
            $hasFindings = \App\Models\Temuan::where('pelapor_id', $user->id)
                ->orWhere('pic_id', $user->id)
                ->exists();

            if ($hasFindings) {
                session()->flash('error', "Karyawan {$k->nama} tidak dapat dihapus karena akun user-nya terikat dengan data temuan. Silakan non-aktifkan saja.");
                return;
            }
        }

        try {
            if ($user) {
                $user->delete();
            }
            $k->delete();
            session()->flash('success', "Karyawan {$k->nama} beserta akun user-nya berhasil dihapus.");
        } catch (\Exception $e) {
            session()->flash('error', "Gagal menghapus karyawan: " . $e->getMessage());
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $karyawans = Karyawan::with('departemen')
            ->when($this->search, fn ($q) => $q
                ->where('nik', 'like', "%{$this->search}%")
                ->orWhere('nama', 'like', "%{$this->search}%")
            )
            ->orderBy('nama')
            ->paginate(15);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('livewire.master-karyawan', [
            'karyawans'  => $karyawans,
            'departemens' => $departemens,
        ]);
    }
}
