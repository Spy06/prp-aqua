<?php

namespace App\Livewire;

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class MasterDataKaryawan extends Component
{
    use WithPagination;

    // Form fields
    public string  $nik           = '';
    public string  $nama          = '';
    public string  $email         = '';
    public string  $departemen_id  = '';

    // State UI & Filters
    public bool    $showForm         = false;
    public ?string $editingNik       = null;
    public string  $search           = '';
    public string  $filterDepartemen = '';

    public function mount(): void
    {
        if (!auth()->user()?->isSuperAdmin()) {
            abort(403, 'Akses Pusat Master Data Karyawan terbatas khusus untuk IT Super Admin.');
        }
    }

    protected function rules(): array
    {
        $user = $this->editingNik ? Karyawan::where('nik', $this->editingNik)->first()?->user : null;
        $userId = $user ? $user->id : null;

        return [
            'nik' => [
                'required',
                'string',
                'max:20',
                Rule::unique('karyawan', 'nik')->ignore($this->editingNik, 'nik'),
                Rule::unique('users', 'nik')->ignore($userId, 'id'),
            ],
            'nama'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'departemen_id' => 'required|exists:departemen,id',
        ];
    }

    protected array $messages = [
        'nik.required'         => 'NIK / Share ID wajib diisi.',
        'nik.unique'           => 'NIK / Share ID ini sudah terdaftar di sistem.',
        'nama.required'        => 'Nama lengkap karyawan wajib diisi.',
        'email.email'          => 'Format alamat email tidak valid.',
        'departemen_id.exists' => 'Departemen tidak ditemukan.',
    ];

    private function checkSuperAdminProtection(Karyawan $k): bool
    {
        if ($k->user?->isSuperAdmin() && !auth()->user()?->isSuperAdmin()) {
            session()->flash('error', 'Akun Super Administrator terproteksi dan tidak dapat diubah/dilihat selain oleh Super Admin.');
            return true;
        }
        return false;
    }

    public function resetForm(): void
    {
        $this->nik           = '';
        $this->nama          = '';
        $this->email         = '';
        $this->departemen_id = '';
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
        $k = Karyawan::with('user')->findOrFail($nik);
        
        if ($this->checkSuperAdminProtection($k)) {
            return;
        }

        $this->editingNik    = $k->nik;
        $this->nik           = $k->nik;
        $this->nama          = $k->nama;
        $this->email         = $k->user?->email ?? '';
        $this->departemen_id = (string) $k->departemen_id;
        $this->showForm      = true;
    }

    public function simpan(): void
    {
        $this->nik   = trim($this->nik);
        $this->email = trim($this->email);
        $this->validate();

        $oldNik     = $this->editingNik;
        $newNik     = $this->nik;
        $cleanEmail = !empty($this->email) ? $this->email : null;

        if ($oldNik) {
            $karyawan = Karyawan::with('user')->findOrFail($oldNik);
            
            if ($this->checkSuperAdminProtection($karyawan)) {
                return;
            }

            $user = $karyawan->user;

            DB::transaction(function () use ($karyawan, $user, $oldNik, $newNik, $cleanEmail) {
                if ($oldNik !== $newNik) {
                    if ($user) {
                        $user->update(['nik' => null]);
                    }

                    DB::table('karyawan')->where('nik', $oldNik)->update([
                        'nik'           => $newNik,
                        'nama'          => $this->nama,
                        'departemen_id' => $this->departemen_id,
                    ]);

                    if ($user) {
                        $user->update([
                            'nik'   => $newNik,
                            'name'  => $this->nama,
                            'email' => $cleanEmail,
                        ]);
                    }
                } else {
                    $karyawan->update([
                        'nama'          => $this->nama,
                        'departemen_id' => $this->departemen_id,
                    ]);

                    if ($user) {
                        $user->update([
                            'name'  => $this->nama,
                            'email' => $cleanEmail,
                        ]);
                    }
                }
            });

            session()->flash('success', "Data karyawan {$this->nama} (NIK: {$newNik}) berhasil diperbarui di pusat data karyawan.");
        } else {
            Karyawan::create([
                'nik'                         => $newNik,
                'nama'                        => $this->nama,
                'departemen_id'               => $this->departemen_id,
                'status_aktif'                => true,
                'is_pic'                      => false,
                'is_anggota_divisi_manajemen' => false,
            ]);

            session()->flash('success', "Karyawan {$this->nama} (NIK: {$newNik}) berhasil ditambahkan ke pusat data karyawan. (Akun login dapat dibuat secara manual di Manajemen Akun User jika diperlukan).");
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function hapus(string $nik): void
    {
        $k = Karyawan::with('user')->findOrFail($nik);
        
        if ($this->checkSuperAdminProtection($k)) {
            return;
        }

        $user = $k->user;

        if ($user) {
            $hasFindings = \App\Models\Temuan::where('pelapor_id', $user->id)
                ->orWhere('pic_id', $user->id)
                ->exists();

            if ($hasFindings) {
                session()->flash('error', "Karyawan {$k->nama} tidak dapat dihapus karena terikat dengan riwayat laporan temuan/observasi. Silakan non-aktifkan statusnya.");
                return;
            }
        }

        try {
            if ($user) {
                $user->delete();
            }
            $k->delete();
            session()->flash('success', "Data karyawan {$k->nama} beserta akunnya berhasil dihapus.");
        } catch (\Exception $e) {
            session()->flash('error', "Gagal menghapus karyawan: " . $e->getMessage());
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
        $karyawans = Karyawan::with(['departemen', 'user'])
            ->where('nama', 'not like', '%super administrator%')
            ->whereDoesntHave('user', function ($q) {
                $q->where('role', 'superadmin');
            })
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

        return view('livewire.master-data-karyawan', [
            'karyawans'   => $karyawans,
            'departemens' => $departemens,
        ]);
    }
}
