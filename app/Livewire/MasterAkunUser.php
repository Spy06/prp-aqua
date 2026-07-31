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
    public string $nik_baru = '';
    public string $role_baru = 'karyawan';
    public string $no_whatsapp_baru = '';

    // Form Edit Akun (Full Access for Super Admin)
    public ?int $editingUserId = null;
    public string $edit_nik = '';
    public string $edit_nama = '';
    public ?int $edit_departemen_id = null;
    public string $edit_role = '';
    public string $edit_no_whatsapp = '';
    public string $edit_password = ''; // Kosongkan jika tidak ingin mengubah password

    public bool $showFormCreate = false;
    public bool $showFormEdit = false;
    public string $search = '';

    // State hasil pencarian NIK
    public ?string $nikSearchResult = null; // nama karyawan dari NIK yang diketik
    public string $nikSearchError = '';

    protected function rulesCreate(): array
    {
        return [
            'nik_baru' => 'required|string|max:20',
            'role_baru' => 'required|in:karyawan,qa,superadmin',
            'no_whatsapp_baru' => 'required|string|regex:/^628[0-9]{8,12}$/',
        ];
    }

    protected array $messages = [
        'no_whatsapp_baru.regex' => 'Format no. WhatsApp harus diawali 628 (contoh: 6281234567890).',
        'edit_no_whatsapp.regex' => 'Format no. WhatsApp harus diawali 628 (contoh: 6281234567890).',
    ];

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
        $this->reset(['nik_baru', 'role_baru', 'no_whatsapp_baru', 'nikSearchResult', 'nikSearchError']);
        $this->role_baru = 'karyawan';
        $this->showFormEdit = false;
        $this->showFormCreate = true;
        $this->resetValidation();
    }

    public function openEdit(int $userId): void
    {
        $user = User::with('karyawan')->findOrFail($userId);
        $this->editingUserId       = $user->id;
        $this->edit_nik           = $user->nik ?? '';
        $this->edit_nama          = $user->name ?? '';
        $this->edit_departemen_id = $user->karyawan?->departemen_id;
        $this->edit_role          = $user->role;
        $this->edit_no_whatsapp   = $user->no_whatsapp ?? '';
        $this->edit_password      = '';

        $this->showFormCreate = false;
        $this->showFormEdit = true;
        $this->resetValidation();
    }

    public function buatAkun(): void
    {
        $this->validate($this->rulesCreate());

        $karyawan = Karyawan::where('nik', $this->nik_baru)->first();

        if (!$karyawan) {
            $this->addError('nik_baru', "NIK {$this->nik_baru} tidak ditemukan di data karyawan. Tambahkan dulu di Master Karyawan.");
            return;
        }

        if (!$karyawan->status_aktif) {
            $this->addError('nik_baru', "Karyawan dengan NIK {$this->nik_baru} tidak aktif. Aktifkan dulu di Master Karyawan.");
            return;
        }

        if ($karyawan->user()->exists()) {
            $this->addError('nik_baru', "NIK {$this->nik_baru} sudah memiliki akun sistem.");
            return;
        }

        User::create([
            'nik' => $this->nik_baru,
            'name' => $karyawan->nama,
            'role' => $this->role_baru,
            'no_whatsapp' => $this->no_whatsapp_baru,
            'password' => Hash::make($this->nik_baru),
        ]);

        session()->flash('success', "Akun untuk {$karyawan->nama} (NIK: {$this->nik_baru}) berhasil dibuat.");
        $this->reset(['nik_baru', 'role_baru', 'no_whatsapp_baru', 'nikSearchResult', 'nikSearchError']);
        $this->showFormCreate = false;
        $this->resetPage();
    }

    public function simpanEdit(): void
    {
        $user = User::with('karyawan')->findOrFail($this->editingUserId);

        $this->validate([
            'edit_nik'           => 'required|string|max:20|unique:users,nik,' . $user->id,
            'edit_nama'          => 'required|string|max:255',
            'edit_departemen_id' => 'nullable|exists:departemens,id',
            'edit_role'          => 'required|in:karyawan,qa,superadmin',
            'edit_no_whatsapp'   => 'required|string|regex:/^628[0-9]{8,12}$/',
            'edit_password'      => 'nullable|string|min:4',
        ]);

        $oldNik = $user->nik;
        $newNik = $this->edit_nik;

        // Update User
        $user->nik         = $newNik;
        $user->name        = $this->edit_nama;
        $user->role        = $this->edit_role;
        $user->no_whatsapp = $this->edit_no_whatsapp;

        if (!empty($this->edit_password)) {
            $user->password = Hash::make($this->edit_password);
        }

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

        session()->flash('success', "Data akun & karyawan {$this->edit_nama} berhasil diperbarui.");
        $this->showFormEdit = false;
        $this->editingUserId = null;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::with('karyawan.departemen')
            ->when(
                $this->search,
                fn($q) => $q
                    ->where('nik', 'like', "%{$this->search}%")
                    ->orWhere('name', 'like', "%{$this->search}%")
            )
            ->orderBy('name')
            ->paginate(15);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('livewire.master-akun-user', [
            'users'       => $users,
            'departemens' => $departemens,
        ]);
    }
}
