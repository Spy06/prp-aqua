<?php

namespace App\Livewire;

use App\Models\Karyawan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasterAkunUser extends Component
{
    use WithPagination;

    // Form Create Akun
    public string $nik_baru = '';
    public string $role_baru = 'karyawan';
    public string $no_whatsapp_baru = '';

    // Form Edit Akun (yang sudah ada)
    public string $edit_no_whatsapp = '';
    public string $edit_role = '';
    public ?int $editingUserId = null;

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
            'role_baru' => 'required|in:karyawan,qa',
            'no_whatsapp_baru' => 'required|string|regex:/^628[0-9]{8,12}$/',
        ];
    }

    protected array $messages = [
        'no_whatsapp_baru.regex' => 'Format no. WhatsApp harus diawali 628 (contoh: 6281234567890).',
        'edit_no_whatsapp.regex' => 'Format no. WhatsApp harus diawali 628 (contoh: 6281234567890).',
    ];

    public function updatedNikBaru(): void
    {
        // Cari karyawan saat NIK diketik
        $this->nikSearchError = '';
        $this->nikSearchResult = null;

        if (strlen($this->nik_baru) >= 3) {
            $k = Karyawan::where('nik', $this->nik_baru)->first();
            if ($k) {
                if (!$k->status_aktif) {
                    $this->nikSearchError = "Karyawan dengan NIK {$this->nik_baru} tidak aktif and tidak bisa dibuatkan akun.";
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
        $user = User::findOrFail($userId);
        $this->editingUserId = $user->id;
        $this->edit_role = $user->role;
        $this->edit_no_whatsapp = $user->no_whatsapp ?? '';
        $this->showFormCreate = false;
        $this->showFormEdit = true;
        $this->resetValidation();
    }

    public function buatAkun(): void
    {
        $this->validate($this->rulesCreate());

        // Validasi NIK: harus terdaftar dan aktif di tabel karyawan
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
            'password' => Hash::make($this->nik_baru), // password default adalah NIK
        ]);

        session()->flash('success', "Akun untuk {$karyawan->nama} (NIK: {$this->nik_baru}) berhasil dibuat.");
        $this->reset(['nik_baru', 'role_baru', 'no_whatsapp_baru', 'nikSearchResult', 'nikSearchError']);
        $this->showFormCreate = false;
        $this->resetPage();
    }

    public function simpanEdit(): void
    {
        $this->validate([
            'edit_role' => 'required|in:karyawan,qa',
            'edit_no_whatsapp' => 'required|string|regex:/^628[0-9]{8,12}$/',
        ]);

        $user = User::findOrFail($this->editingUserId);

        $updateData = [
            'role' => $this->edit_role,
            'no_whatsapp' => $this->edit_no_whatsapp,
        ];

        $user->update($updateData);

        session()->flash('success', "Akun {$user->name} berhasil diperbarui.");
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

        return view('livewire.master-akun-user', [
            'users' => $users,
        ]);
    }
}
