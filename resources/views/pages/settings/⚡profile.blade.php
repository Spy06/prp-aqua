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

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Pengaturan Profil') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profil')" :subheading="__('Perbarui nama tampilan dan nomor WhatsApp Anda')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">

            {{-- NIK (hanya tampilan, tidak bisa diubah) --}}
            <flux:input
                :label="__('NIK (Nomor Induk Karyawan)')"
                :value="Auth::user()->nik ?? '-'"
                type="text"
                readonly
                disabled
                class="opacity-60 cursor-not-allowed"
            />

            {{-- Nama tampilan --}}
            <flux:input
                wire:model="name"
                :label="__('Nama')"
                type="text"
                required
                autofocus
                autocomplete="name"
            />

            {{-- Nomor WhatsApp untuk notifikasi --}}
            <flux:input
                wire:model="no_whatsapp"
                :label="__('Nomor WhatsApp')"
                type="text"
                autocomplete="tel"
                placeholder="6281234567890"
                :description="__('Dipakai untuk notifikasi temuan PRP. Format: 628xxx tanpa spasi atau tanda +')"
            />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-profile-button">
                    {{ __('Simpan') }}
                </flux:button>
            </div>
        </form>

        {{-- Hapus akun dinonaktifkan — penghapusan akun hanya oleh QA (admin master) --}}
    </x-pages::settings.layout>
</section>
