<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login - PRP Aqua' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="font-sans antialiased text-zinc-900 dark:text-zinc-50 flex items-center justify-center min-h-screen relative overflow-hidden bg-zinc-900">
    <!-- Factory Background Image -->
    <div class="absolute inset-0 z-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1565514020179-026b92b84bb6?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80'); filter: blur(6px) brightness(0.5); transform: scale(1.05);"></div>

    <div class="relative z-10 w-full max-w-[440px] px-8 py-12 mx-4 bg-[#E0F7FA]/95 dark:bg-[#1E3A4A]/95 backdrop-blur-md rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] flex flex-col items-center border border-transparent dark:border-[#0B141A]/50">
        <!-- Logo -->
        <div class="w-16 h-16 bg-[#008B9D] dark:bg-[#00D4FF] rounded-full flex items-center justify-center mb-5 shadow-lg text-white dark:text-[#0B141A]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        </div>
        <h1 class="text-2xl font-bold text-[#00606D] dark:text-white mb-1.5">Sistem Verifikasi PRP</h1>
        <p class="text-[13px] text-[#455A64] dark:text-[#607D8B] mb-8 font-medium">Masuk ke sistem internal</p>

        <div class="w-full">
            {{ $slot }}
        </div>

        <p class="mt-8 text-[11px] text-[#546E7A] dark:text-[#607D8B] font-medium text-center">Dengan masuk, Anda menyetujui kebijakan privasi internal.</p>
    </div>
</body>
</html>
