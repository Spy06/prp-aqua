<?php

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Pengaturan Profil')] class extends Component {

    public string $name = '';
    public ?string $email = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name  = $user->name ?? $user->karyawan?->nama ?? '';
        $this->email = $user->email ?? '';
    }

    /**
     * Tentukan layout secara dinamis berdasarkan role user.
     */
    public function rendering($view): void
    {
        $system = session('login_system', 'sivera');
        if ($system === 'bosq') {
            $layout = 'layouts.bosq';
            $title  = __('Pengaturan Profil — BOS\'Q');
        } else {
            $user = Auth::user();
            $isPicUser = $user && $user->role === 'karyawan' && (
                \App\Models\Temuan::where('pic_id', $user->id)->exists() ||
                \App\Models\Karyawan::where('nik', $user->nik)->where('status_aktif', true)->exists()
            );

            if ($user && ($user->role === 'qa' || $user->isSuperAdmin() || $isPicUser)) {
                $layout = 'layouts.qa';
            } else {
                $layout = 'layouts.app';
            }
            $title  = __('Pengaturan Profil — SIVERA');
        }
        $view->layout($layout, ['title' => $title]);
    }

    /**
     * Update nama tampilan dan alamat email notifikasi.
     */
    public function updateProfileInformation(): void
    {
        $this->email = trim((string)$this->email);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        if (!empty($this->email)) {
            $rules['email'] = ['email', 'max:255'];
        }

        $this->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.email'   => 'Format alamat email tidak valid.',
        ]);

        $user = Auth::user();
        $user->name  = $this->name;
        $user->email = !empty($this->email) ? $this->email : null;
        $user->save();

        session()->flash('success', 'Profil & email notifikasi berhasil diperbarui.');
        try {
            Flux::toast(variant: 'success', text: __('Profil & email notifikasi berhasil diperbarui.'));
        } catch (\Throwable $e) {
            // Ignore if Flux toast is not registered
        }
    }
}; ?>

<div style="max-width:900px;margin:0 auto;">
    @include('partials.settings-heading')

    <x-pages::settings.layout :heading="__('Profil')" :subheading="__('Perbarui nama tampilan dan alamat email notifikasi Anda')">
        
        @if(session('success'))
            <div class="balert balert-success" style="margin-top:12px;margin-bottom:12px;padding:10px 14px;border-radius:8px;background:#e8f5e9;color:#2e7d32;display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600;">
                <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="updateProfileInformation" style="display:flex;flex-direction:column;gap:16px;margin-top:12px;">

            {{-- NIK --}}
            <div>
                <label class="blabel">NIK (Nomor Induk Karyawan)</label>
                <input type="text" value="{{ Auth::user()->nik ?? '-' }}" class="binput" disabled readonly style="opacity:0.7;cursor:not-allowed;background:var(--bsur);" />
            </div>

            {{-- Departemen (Read-Only dari database karyawan QA SIVERA) --}}
            <div>
                <label class="blabel">Departemen</label>
                <input type="text" value="{{ Auth::user()->karyawan?->departemen?->nama_departemen ?? 'Tanpa Departemen' }}" class="binput" disabled readonly style="opacity:0.7;cursor:not-allowed;background:var(--bsur);" />
            </div>

            {{-- Nama tampilan --}}
            <div>
                <label for="name" class="blabel">Nama Lengkap <span style="color:var(--be, #ef4444);">*</span></label>
                <input type="text" id="name" wire:model="name" class="binput" required autocomplete="name" />
                @error('name') <span class="berr-msg" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span> @enderror
            </div>

            {{-- Alamat Email untuk Notifikasi --}}
            <div>
                <label for="email" class="blabel">Alamat Email Notifikasi</label>
                <input type="email" id="email" wire:model="email" class="binput" placeholder="nama@namaperusahaan.com" autocomplete="email" />
                <span style="font-size:11.5px;color:var(--btxt2);margin-top:4px;display:block;">
                    Alamat email ini digunakan oleh sistem untuk mengirimkan notifikasi penugasan PIC, audit, & observasi.
                </span>
                @error('email') <span class="berr-msg" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span> @enderror
            </div>

            @php
                $system = session('login_system', 'sivera');
                if (Auth::user()?->isSuperAdmin()) {
                    $homeRoute = route('qa.master.akun');
                } else {
                    $homeRoute = $system === 'bosq'
                        ? (Auth::user()?->role === 'qa' ? route('bosq.qa.dashboard') : route('bosq.beranda'))
                        : (Auth::user()?->role === 'qa' ? route('qa.dashboard') : route('beranda'));
                }
            @endphp
            <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
                <button type="submit" wire:loading.attr="disabled" class="bbtn bbtn-primary bbtn-sm" data-test="update-profile-button">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    <span wire:loading.remove wire:target="updateProfileInformation">Simpan Profil</span>
                    <span wire:loading wire:target="updateProfileInformation">Menyimpan...</span>
                </button>
                <a href="{{ $homeRoute }}" class="bbtn bbtn-secondary bbtn-sm" style="text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
                    Kembali
                </a>
            </div>
        </form>
    </x-pages::settings.layout>
</div>
