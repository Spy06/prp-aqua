<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class MasterAkunUser extends Component
{
    use WithPagination;

    // Form Create Akun
    public string $nik_baru  = '';
    public string $role_baru = 'karyawan';

    // Form Edit Akun
    public ?int $editingUserId      = null;
    public string $edit_nik         = '';
    public string $edit_nama        = '';
    public string $edit_email       = '';
    public ?int $edit_departemen_id = null;
    public string $edit_role        = '';

    // UI state & filters
    public bool $showFormCreate     = false;
    public bool $showFormEdit       = false;
    public string $search           = '';
    public string $filterDepartemen = '';

    // State hasil pencarian NIK
    public ?string $nikSearchResult = null;
    public string $nikSearchError   = '';

    protected function rulesCreate(): array
    {
        return [
            'nik_baru'  => 'required|string|max:20',
            'role_baru' => 'required|in:karyawan,qa',
        ];
    }

    public function updatedNikBaru(): void
    {
        $this->nikSearchError = '';
        $this->nikSearchResult = null;

        if (strlen($this->nik_baru) >= 3) {
            $k = Karyawan::where('nik', $this->nik_baru)->first();
            if ($k) {
                if (!$k->status_aktif) {
                    $this->nikSearchError = "Karyawan dengan NIK {$this->nik_baru} tidak aktif dan tidak bisa dibuatkan akun.";
                } elseif ($k->user()->exists()) {
                    $this->nikSearchError = "NIK {$this->nik_baru} sudah memiliki akun sistem.";
                } else {
                    $this->nikSearchResult = $k->nama;
                }
            } else {
                $this->nikSearchError = "NIK {$this->nik_baru} tidak ditemukan di data karyawan.";
            }
        }
    }

    public function openCreate(): void
    {
        $this->reset(['nik_baru', 'role_baru', 'nikSearchResult', 'nikSearchError']);
        $this->role_baru = 'karyawan';
        $this->showFormEdit = false;
        $this->showFormCreate = true;
        $this->resetValidation();
    }

    public function openEdit(int $userId): void
    {
        $user = User::with('karyawan')->findOrFail($userId);

        if ($user->isSuperAdmin() && !auth()->user()?->isSuperAdmin()) {
            session()->flash('error', 'Akun Super Admin terproteksi dan hanya dapat dikelola melalui Portal Khusus IT Super Admin.');
            return;
        }

        $this->editingUserId       = $user->id;
        $this->edit_nik           = $user->nik ?? '';
        $this->edit_nama          = $user->name ?? '';
        $this->edit_email         = $user->email ?? '';
        $this->edit_departemen_id = $user->karyawan?->departemen_id;
        $this->edit_role          = ($user->role === 'superadmin') ? 'qa' : $user->role;

        $this->showFormCreate = false;
        $this->showFormEdit = true;
        $this->resetValidation();
    }

    public function buatAkun(): void
    {
        $this->validate($this->rulesCreate());

        $karyawan = Karyawan::with('user')->where('nik', $this->nik_baru)->first();

        if (!$karyawan) {
            $this->addError('nik_baru', "NIK {$this->nik_baru} tidak ditemukan di data karyawan. Tambahkan dulu di Master PIC.");
            return;
        }

        if (!$karyawan->status_aktif) {
            $this->addError('nik_baru', "Karyawan dengan NIK {$this->nik_baru} tidak aktif. Aktifkan dulu di Master PIC.");
            return;
        }

        if ($karyawan->user()->exists()) {
            $this->addError('nik_baru', "NIK {$this->nik_baru} sudah memiliki akun sistem.");
            return;
        }

        User::create([
            'nik'      => $this->nik_baru,
            'name'     => $karyawan->nama,
            'email'    => $karyawan->user?->email ?: null,
            'role'     => $this->role_baru,
            'password' => Hash::make($this->nik_baru),
        ]);

        session()->flash('success', "Akun untuk {$karyawan->nama} (NIK: {$this->nik_baru}) berhasil dibuat dengan role " . strtoupper($this->role_baru) . ".");
        $this->reset(['nik_baru', 'role_baru', 'nikSearchResult', 'nikSearchError']);
        $this->showFormCreate = false;
        $this->resetPage();
    }

    public function simpanEdit(): void
    {
        $user = User::with('karyawan')->findOrFail($this->editingUserId);

        if ($user->isSuperAdmin() && !auth()->user()?->isSuperAdmin()) {
            session()->flash('error', 'Akun Super Admin terproteksi dan hanya dapat dikelola melalui Portal Khusus IT Super Admin.');
            return;
        }

        $this->validate([
            'edit_nik'           => 'required|string|max:20|unique:users,nik,' . $user->id,
            'edit_nama'          => 'required|string|max:255',
            'edit_email'         => 'nullable|email|max:255',
            'edit_departemen_id' => 'nullable|exists:departemen,id',
            'edit_role'          => 'required|in:karyawan,qa',
        ]);

        $oldNik = $user->nik;
        $newNik = $this->edit_nik;

        // Update User
        $user->nik   = $newNik;
        $user->name  = $this->edit_nama;
        $user->email = $this->edit_email ?: null;
        $user->role  = $this->edit_role;

        $user->save();

        // Update Karyawan
        if ($user->karyawan) {
            $user->karyawan->update([
                'nik'           => $newNik,
                'nama'          => $this->edit_nama,
                'departemen_id' => $this->edit_departemen_id,
            ]);
        } elseif ($oldNik) {
            Karyawan::where('nik', $oldNik)->update([
                'nik'           => $newNik,
                'nama'          => $this->edit_nama,
                'departemen_id' => $this->edit_departemen_id,
            ]);
        }

        session()->flash('success', "Akses & data akun {$this->edit_nama} berhasil diperbarui (Role: " . strtoupper($this->edit_role) . ").");
        $this->showFormEdit = false;
        $this->editingUserId = null;
    }

    public function hapusAkun(int $userId): void
    {
        $user = User::with('karyawan')->findOrFail($userId);

        if ($user->isSuperAdmin() && !auth()->user()?->isSuperAdmin()) {
            session()->flash('error', 'Akun Super Admin terproteksi dan tidak dapat dihapus.');
            return;
        }

        // Cek keterikatan temuan audit
        $hasFindings = \App\Models\Temuan::where('pelapor_id', $user->id)
            ->orWhere('pic_id', $user->id)
            ->exists();

        if ($hasFindings) {
            session()->flash('error', "Akun '{$user->name}' tidak dapat dihapus karena terikat dengan data temuan audit. Silakan non-aktifkan statusnya di Master PIC.");
            return;
        }

        try {
            $userName = $user->name;
            // Hanya hapus data akun login (users table). Data karyawan di Master PIC tetap utuh.
            $user->delete();

            session()->flash('success', "Akun login '{$userName}' berhasil dihapus dari Manajemen Akun User (data karyawan di Master PIC tetap tersimpan).");
        } catch (\Exception $e) {
            session()->flash('error', "Gagal menghapus akun: " . $e->getMessage());
        }
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
        $isSuperAdmin = auth()->user()?->isSuperAdmin() ?? false;

        $users = User::with('karyawan.departemen')
            ->when(!$isSuperAdmin, function ($query) {
                // Sembunyikan akun Super Admin dari tampilan QA Admin biasa
                $query->where('role', '!=', 'superadmin');
            })
            ->when($this->filterDepartemen, function ($q) {
                $q->whereHas('karyawan', fn ($kq) => $kq->where('departemen_id', $this->filterDepartemen));
            })
            ->when(
                $this->search,
                fn($q) => $q
                    ->where(function ($sub) {
                        $sub->where('nik', 'like', "%{$this->search}%")
                            ->orWhere('name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%");
                    })
            )
            ->orderBy('name')
            ->paginate(15);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('livewire.master-akun-user', [
            'users'        => $users,
            'departemens'  => $departemens,
            'isSuperAdmin' => $isSuperAdmin,
        ]);
    }
}
