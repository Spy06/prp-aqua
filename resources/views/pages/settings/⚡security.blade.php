<?php

use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Ganti Password')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Tentukan layout secara dinamis berdasarkan role user.
     */
    public function rendering($view): void
    {
        $system = session('login_system', 'sivera');
        if ($system === 'bosq') {
            $layout = 'layouts.bosq';
            $title  = __('Ganti Password — BOS\'Q');
        } else {
            $layout = Auth::user()?->role === 'qa' ? 'layouts.qa' : 'layouts.app';
            $title  = __('Ganti Password — SIVERA');
        }
        $view->layout($layout, ['title' => $title]);
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('Password berhasil diperbarui.'));
    }
}; ?>

<div style="max-width:900px;margin:0 auto;">
    @include('partials.settings-heading')

    <x-pages::settings.layout :heading="__('Ganti Password')" :subheading="__('Pastikan akun Anda menggunakan kata sandi yang kuat agar tetap aman')">
        <form wire:submit="updatePassword" style="display:flex;flex-direction:column;gap:16px;margin-top:12px;">

            <div>
                <label for="current_password" class="blabel">Kata Sandi Saat Ini <span style="color:var(--error);">*</span></label>
                <input type="password" id="current_password" wire:model="current_password" class="binput" required autocomplete="current-password" />
                @error('current_password') <span class="berr">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="blabel">Kata Sandi Baru <span style="color:var(--error);">*</span></label>
                <input type="password" id="password" wire:model="password" class="binput" required autocomplete="new-password" />
                @error('password') <span class="berr">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="blabel">Konfirmasi Kata Sandi Baru <span style="color:var(--error);">*</span></label>
                <input type="password" id="password_confirmation" wire:model="password_confirmation" class="binput" required autocomplete="new-password" />
                @error('password_confirmation') <span class="berr">{{ $message }}</span> @enderror
            </div>

            @php
                $system = session('login_system', 'sivera');
                $homeRoute = $system === 'bosq'
                    ? (Auth::user()?->role === 'qa' ? route('bosq.qa.dashboard') : route('bosq.beranda'))
                    : (Auth::user()?->role === 'qa' ? route('qa.dashboard') : route('beranda'));
            @endphp
            <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
                <button type="submit" class="bbtn bbtn-primary bbtn-sm" data-test="update-password-button">
                    <span class="material-symbols-outlined" style="font-size:16px;">key</span>
                    Simpan Password Baru
                </button>
                <a href="{{ $homeRoute }}" class="bbtn bbtn-secondary bbtn-sm" style="text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
                    Kembali
                </a>
            </div>
        </form>
    </x-pages::settings.layout>
</div>
