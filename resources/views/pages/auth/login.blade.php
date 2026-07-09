<x-layouts::auth :title="__('Masuk')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Masuk ke Sistem PRP')" :description="__('Masukkan NIK dan password Anda untuk masuk')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- NIK (menggantikan email) -->
            <flux:input
                name="nik"
                :label="__('NIK (Nomor Induk Karyawan)')"
                :value="old('nik')"
                type="text"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan NIK Anda"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Ingat saya')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Masuk') }}
                </flux:button>
            </div>
        </form>

        {{-- Tidak ada link ke registrasi — pendaftaran akun hanya dilakukan oleh QA --}}
    </div>
</x-layouts::auth>
