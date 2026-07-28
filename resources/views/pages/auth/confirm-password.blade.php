@php
    $system = session('login_system', 'sivera');
    $backUrl = $system === 'bosq'
        ? (auth()->user()?->role === 'qa' ? route('bosq.qa.dashboard') : route('bosq.beranda'))
        : (auth()->user()?->role === 'qa' ? route('qa.dashboard') : route('beranda'));
@endphp
<x-layouts::auth :title="__('Confirm password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <div class="flex items-center gap-3">
                <a href="{{ $backUrl }}" class="w-1/2 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 transition-all text-decoration-none" style="text-decoration: none;">
                    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
                    Kembali
                </a>
                <flux:button variant="primary" type="submit" class="w-1/2" data-test="confirm-password-button">
                    {{ __('Confirm') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
