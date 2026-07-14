<x-layouts::auth :title="__('Masuk')">
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="w-full flex flex-col gap-4">
        @csrf

        <!-- NIK -->
        <div>
            <label for="nik" class="block text-[13px] font-semibold text-[#455A64] dark:text-zinc-200 mb-1.5">NIK Karyawan</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#607D8B] dark:text-zinc-400">
                    <!-- id card icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                </div>
                <input id="nik" name="nik" type="text" value="{{ old('nik') }}" required autofocus placeholder="Masukkan NIK" class="block w-full pl-11 pr-3 py-2.5 bg-white dark:bg-[#0B141A] border border-[#B0BEC5] dark:border-[#1E3A4A] rounded-lg text-sm text-[#171717] dark:text-zinc-100 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-[#008B9D] dark:focus:ring-[#00D4FF] focus:border-[#008B9D] dark:focus:border-[#00D4FF] transition-colors">
            </div>
            @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[13px] font-semibold text-[#455A64] dark:text-zinc-200 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#607D8B] dark:text-zinc-400">
                    <!-- lock icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" name="password" type="password" required placeholder="Masukkan Password" class="block w-full pl-11 pr-3 py-2.5 bg-white dark:bg-[#0B141A] border border-[#B0BEC5] dark:border-[#1E3A4A] rounded-lg text-sm text-[#171717] dark:text-zinc-100 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-[#008B9D] dark:focus:ring-[#00D4FF] focus:border-[#008B9D] dark:focus:border-[#00D4FF] transition-colors">
            </div>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Forgot -->
        <div class="flex justify-end mt-1">
            <a href="#" class="text-[12px] font-bold text-[#008B9D] dark:text-[#00D4FF] hover:text-[#00606D] dark:hover:text-[#00a6c7] transition">Lupa Password?</a>
        </div>

        <button type="submit" class="mt-3 w-full bg-[#008B9D] dark:bg-[#00D4FF] hover:bg-[#00606D] dark:hover:bg-[#00a6c7] text-white dark:text-[#0B141A] font-bold py-3 px-4 rounded-xl flex justify-center items-center gap-2 transition-colors focus:ring-4 focus:ring-[#008B9D]/30 dark:focus:ring-[#00D4FF]/30 shadow-md">
            Masuk
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" transform="matrix(-1 0 0 1 24 0)"></path></svg>
        </button>
    </form>
</x-layouts.auth>
