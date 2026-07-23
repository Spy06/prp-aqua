<?php

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Pengaturan Profil')] class extends Component {

    public string $name = '';
    public ?string $no_whatsapp = null;

    /**
     * Mount the component.
     * Menggunakan NIK-based profile — email tidak dipakai.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name ?? $user->karyawan?->nama ?? '';
        $this->no_whatsapp = $user->no_whatsapp ?? '';
    }

    /**
     * Tentukan layout secara dinamis berdasarkan role user.
     */
    public function rendering($view): void
    {
        $layout = Auth::user()?->role === 'qa' ? 'layouts.qa' : 'layouts.app';
        $view->layout($layout, ['title' => __('Pengaturan Profil — SIVERA')]);
    }

    /**
     * Update nama tampilan dan nomor WhatsApp.
     */
    public function updateProfileInformation(): void
    {
        $validated = $this->validate([
            'name'         => ['required', 'string', 'max:255'],
            'no_whatsapp'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
        ], [
            'no_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka (contoh: 6281234567890).',
        ]);

        $user = Auth::user();
        $user->name        = $validated['name'];
        $user->no_whatsapp = $validated['no_whatsapp'] ?: null;
        $user->save();

        Flux::toast(variant: 'success', text: __('Profil berhasil diperbarui.'));
    }
}; ?>

<div style="max-width:900px;margin:0 auto;">
    @include('partials.settings-heading')

    <x-pages::settings.layout :heading="__('Profil')" :subheading="__('Perbarui nama tampilan dan nomor WhatsApp Anda')">
        <form wire:submit="updateProfileInformation" style="display:flex;flex-direction:column;gap:16px;margin-top:12px;">

            {{-- NIK --}}
            <div>
                <label class="blabel">NIK (Nomor Induk Karyawan)</label>
                <input type="text" value="{{ Auth::user()->nik ?? '-' }}" class="binput" disabled readonly style="opacity:0.7;cursor:not-allowed;background:var(--bsur);" />
            </div>

            {{-- Nama tampilan --}}
            <div>
                <label for="name" class="blabel">Nama Lengkap <span style="color:var(--error);">*</span></label>
                <input type="text" id="name" wire:model="name" class="binput" required autocomplete="name" />
                @error('name') <span class="berr">{{ $message }}</span> @enderror
            </div>

            {{-- Nomor WhatsApp untuk notifikasi --}}
            <div>
                <label for="no_whatsapp" class="blabel">Nomor WhatsApp Notifikasi</label>
                <input type="text" id="no_whatsapp" wire:model="no_whatsapp" class="binput" placeholder="6281234567890" autocomplete="tel" />
                <span style="font-size:11px;color:var(--btxt2);margin-top:4px;display:block;">
                    Dipakai untuk notifikasi WA temuan. Format: 628xxx tanpa spasi atau tanda +
                </span>
                @error('no_whatsapp') <span class="berr">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top:8px;">
                <button type="submit" class="bbtn bbtn-primary bbtn-sm" data-test="update-profile-button">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    Simpan Profil
                </button>
            </div>
        </form>
    </x-pages::settings.layout>
</div>
