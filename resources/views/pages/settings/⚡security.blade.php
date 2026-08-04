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
            ], [
                'password.min' => 'Kata sandi baru minimal 5 karakter.',
                'password.max' => 'Kata sandi baru maksimal 100 karakter.',
                'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
                'current_password.current_password' => 'Kata sandi saat ini salah.',
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('success', 'Kata sandi Anda berhasil diperbarui.');

        try {
            Flux::toast(variant: 'success', text: __('Password berhasil diperbarui.'));
        } catch (\Throwable $e) {
            // Ignore if Flux toast is not registered
        }
    }
}; ?>

<div style="max-width:900px;margin:0 auto;">
    @include('partials.settings-heading')

    <x-pages::settings.layout :heading="__('Ganti Password')" :subheading="__('Kelola kata sandi akun Anda (minimal 5 karakter, maksimal 100 karakter bebas)')">
        
        @if(session('success'))
            <div class="balert balert-success" style="margin-top:12px;margin-bottom:12px;padding:10px 14px;border-radius:8px;background:#e8f5e9;color:#2e7d32;display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600;">
                <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="updatePassword" style="display:flex;flex-direction:column;gap:16px;margin-top:12px;">

            <div>
                <label for="current_password" class="blabel">Kata Sandi Saat Ini <span style="color:var(--error, #ef4444);">*</span></label>
                <input type="password" id="current_password" wire:model="current_password" class="binput" required autocomplete="current-password" />
                @error('current_password') <span class="berr-msg" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="blabel">Kata Sandi Baru <span style="color:var(--error, #ef4444);">*</span></label>
                <input type="password" id="password" wire:model="password" class="binput" required autocomplete="new-password" />
                <span style="font-size:11.5px;color:var(--btxt2);margin-top:4px;display:block;">
                    Password minimal 5 karakter dan maksimal 100 karakter (dapat berupa kombinasi huruf, angka, maupun simbol bebas).
                </span>
                @error('password') <span class="berr-msg" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="blabel">Konfirmasi Kata Sandi Baru <span style="color:var(--error, #ef4444);">*</span></label>
                <input type="password" id="password_confirmation" wire:model="password_confirmation" class="binput" required autocomplete="new-password" />
                @error('password_confirmation') <span class="berr-msg" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span> @enderror
            </div>

            @php
                $system = session('login_system', 'sivera');
                $homeRoute = $system === 'bosq'
                    ? (Auth::user()?->role === 'qa' ? route('bosq.qa.dashboard') : route('bosq.beranda'))
                    : (Auth::user()?->role === 'qa' ? route('qa.dashboard') : route('beranda'));
            @endphp
            <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
                <button type="submit" wire:loading.attr="disabled" class="bbtn bbtn-primary bbtn-sm" data-test="update-password-button">
                    <span class="material-symbols-outlined" style="font-size:16px;">key</span>
                    <span wire:loading.remove wire:target="updatePassword">Simpan Password Baru</span>
                    <span wire:loading wire:target="updatePassword">Menyimpan...</span>
                </button>
                <a href="{{ $homeRoute }}" class="bbtn bbtn-secondary bbtn-sm" style="text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
                    Kembali
                </a>
            </div>
        </form>
    </x-pages::settings.layout>
</div>
