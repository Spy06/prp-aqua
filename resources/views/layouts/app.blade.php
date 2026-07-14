<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem Verifikasi PRP' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
<body class="font-sans antialiased text-zinc-900 bg-cyan-50/30 md:bg-zinc-50 min-h-screen flex flex-col md:flex-row">
    
    <!-- Desktop Sidebar (Hidden on mobile) -->
    <aside class="hidden md:flex flex-col w-64 bg-cyan-50/50 border-r border-cyan-100 min-h-screen fixed left-0 top-0 bottom-0 z-20">
        <div class="p-6 flex items-center gap-3 border-b border-cyan-100">
            <div class="w-8 h-8 bg-white dark:bg-zinc-100 border border-teal-100 dark:border-zinc-200 text-teal-700 rounded flex justify-center items-center shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-teal-800 text-sm">Verifikasi PRP</h2>
                <p class="text-[10px] text-zinc-500 font-medium">Internal System</p>
            </div>
        </div>

        <div class="flex-1 py-6 flex flex-col gap-1">
            @auth
            @if(auth()->user()->role === 'pelapor')
                <div class="px-4 mb-2">
                    <a href="{{ route('beranda') }}" class="flex items-center gap-3 px-4 py-2.5 bg-teal-800 text-white rounded-lg font-medium text-sm shadow-md hover:bg-teal-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Report
                    </a>
                </div>
            @endif
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('dashboard') ? 'bg-teal-100/50 text-teal-800 font-bold border-r-2 border-teal-800' : 'text-zinc-600 hover:bg-cyan-50 font-medium' }} text-sm transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            @endauth
        </div>

        <div class="p-6 border-t border-cyan-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 text-zinc-600 hover:text-red-600 text-sm font-medium transition-colors w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Top Bar -->
    <header class="md:hidden bg-white/80 dark:bg-zinc-100/80 backdrop-blur-md border-b border-[#E0F7FA] dark:border-zinc-200 px-4 py-3 flex justify-between items-center sticky top-0 z-20">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-[#008B9D] dark:text-[#00D4FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <h1 class="font-bold text-[#008B9D] dark:text-white text-lg">Sistem Verifikasi PRP</h1>
        </div>
        <div class="flex items-center gap-3">
            <button class="text-[#008B9D] hover:text-[#00606D] dark:text-[#00D4FF] dark:hover:text-[#00a6c7]"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg></button>
            @auth
            <flux:dropdown align="end" offset="4">
                <button data-flux-profile class="w-8 h-8 rounded-full bg-[#E0F7FA] overflow-hidden border border-[#00ACC1]">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=008B9D&background=E0F7FA" alt="Avatar">
                </button>
                <flux:menu>
                    <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate>Profile</flux:menu.item>
                    <flux:menu.item :href="route('security.edit')" icon="shield-check" wire:navigate>Password & Security</flux:menu.item>
                    <flux:menu.item :href="route('appearance.edit')" icon="swatch" wire:navigate>Theme</flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-600">Log out</flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 w-full relative pb-20 md:pb-0 min-h-screen flex flex-col">
        <!-- Desktop Header Area -->
        <div class="hidden md:flex justify-between items-center px-8 py-4 bg-zinc-50/50">
            <div>
                <x-slot name="header">
                    {{-- Can be injected by views --}}
                </x-slot>
            </div>
            <div class="flex items-center gap-4 ml-auto">
                <button class="text-[#008B9D] dark:text-[#00D4FF] hover:text-[#00606D] dark:hover:text-[#00a6c7] transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg></button>
                @auth
                <flux:dropdown align="end" offset="4">
                    <button data-flux-profile class="flex items-center gap-2 bg-white dark:bg-zinc-100 pl-3 pr-1 py-1 rounded-full border border-[#00ACC1]/30 dark:border-zinc-200 shadow-sm hover:bg-[#E0F7FA] dark:hover:bg-zinc-200 transition-colors focus:outline-none focus:ring-2 focus:ring-[#008B9D]">
                        <span class="text-sm font-bold text-[#00606D] dark:text-[#E0F7FA]">{{ auth()->user()->name }}</span>
                        <div class="w-7 h-7 rounded-full overflow-hidden ml-2 border border-[#00ACC1]/50 dark:border-[#00D4FF]/50">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=008B9D&background=E0F7FA" alt="Avatar">
                        </div>
                    </button>
                    <flux:menu>
                        <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate>Profile</flux:menu.item>
                        <flux:menu.item :href="route('security.edit')" icon="shield-check" wire:navigate>Password & Security</flux:menu.item>
                        <flux:menu.item :href="route('appearance.edit')" icon="swatch" wire:navigate>Theme</flux:menu.item>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-600 hover:bg-red-50">Log out</flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
                @endauth
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4 md:px-8 md:py-2 flex-1">
            {{ $slot }}
        </div>
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 w-full bg-white dark:bg-zinc-100 border-t border-zinc-200 flex justify-around items-center px-2 py-2 z-30 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
        <a href="{{ route('beranda') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('beranda') ? 'text-teal-700' : 'text-zinc-500' }}">
            <div class="{{ request()->routeIs('beranda') ? 'bg-teal-700 text-white' : '' }} p-1.5 rounded-full mb-1 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Home</span>
        </a>
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard') ? 'text-teal-700' : 'text-zinc-500' }}">
            <div class="{{ request()->routeIs('dashboard') ? 'bg-teal-700 text-white' : '' }} p-1.5 rounded-full mb-1 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <span class="text-[10px] font-bold">Issues</span>
        </a>
        
        <div class="flex flex-col items-center p-2 text-zinc-500">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex flex-col items-center">
                    <div class="p-1.5 rounded-full mb-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg></div>
                    <span class="text-[10px] font-bold">Logout</span>
                </button>
            </form>
        </div>
    </nav>
    @fluxScripts
    @livewireScripts
</body>
</html>
