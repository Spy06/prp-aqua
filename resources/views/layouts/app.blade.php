<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        function toggleTheme() {
            if(document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
        
        document.addEventListener('livewire:navigated', () => {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $title ?? 'Sistem Verifikasi PRP' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .filled-icon {
            font-variation-settings: 'FILL' 1;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col pb-20 lg:pb-0 lg:flex-row">
    <!-- TopNavBar Mobile -->
    <header class="lg:hidden bg-surface flex justify-between items-center w-full px-4 h-14 sticky top-0 z-50 border-b border-outline-variant shadow-sm">
        <div class="flex items-center gap-2 min-w-0">
            <div class="w-7 h-7 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container shrink-0">
                <span class="material-symbols-outlined text-base">factory</span>
            </div>
            <h1 class="text-sm font-bold text-primary truncate">Verifikasi PRP</h1>
        </div>
        <div class="flex items-center gap-1">
            <!-- Theme Toggle Mobile -->
            <button onclick="toggleTheme()" class="text-on-surface-variant hover:bg-surface-container-low transition-colors rounded-full p-2 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">dark_mode</span>
            </button>
            @auth
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-on-surface-variant hover:bg-surface-container-low transition-colors rounded-full p-2 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">logout</span>
                </button>
            </form>
            @endauth
        </div>
    </header>

    <!-- SideNavBar Desktop -->
    <nav class="hidden lg:flex flex-col w-[260px] h-screen fixed left-0 top-0 overflow-y-auto bg-surface-container-low border-r border-outline-variant z-40">
        <div class="p-lg border-b border-outline-variant flex items-center gap-md">
            <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                <span class="material-symbols-outlined">factory</span>
            </div>
            <div>
                <h1 class="font-headline-lg text-headline-lg font-bold text-primary">Verifikasi PRP</h1>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Internal System</p>
            </div>
        </div>
        

        <div class="flex-1 py-md flex flex-col gap-sm px-sm">
            @auth
                @if(auth()->user()->role === 'qa')
                    <p class="px-md text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2 mt-4">QA Dashboard</p>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.dashboard') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.dashboard') }}" wire:navigate>
                        <span class="material-symbols-outlined {{ request()->routeIs('qa.dashboard') ? 'filled-icon' : '' }}">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard Temuan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.rekap') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.rekap') }}" wire:navigate>
                        <span class="material-symbols-outlined {{ request()->routeIs('qa.rekap') ? 'filled-icon' : '' }}">calendar_month</span>
                        <span class="font-label-md text-label-md">Rekap Periode</span>
                    </a>
                    
                    <p class="px-md text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2 mt-4">Master Data</p>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.master.karyawan') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.master.karyawan') }}" wire:navigate>
                        <span class="material-symbols-outlined">group</span>
                        <span class="font-label-md text-label-md">Karyawan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.master.departemen') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.master.departemen') }}" wire:navigate>
                        <span class="material-symbols-outlined">domain</span>
                        <span class="font-label-md text-label-md">Departemen</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.master.klausul') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.master.klausul') }}" wire:navigate>
                        <span class="material-symbols-outlined">rule</span>
                        <span class="font-label-md text-label-md">Klausul PRP</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.master.akun') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.master.akun') }}" wire:navigate>
                        <span class="material-symbols-outlined">manage_accounts</span>
                        <span class="font-label-md text-label-md">Akun User</span>
                    </a>
                @else
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('beranda') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('beranda') }}" wire:navigate>
                        <span class="material-symbols-outlined {{ request()->routeIs('beranda') ? 'filled-icon' : '' }}">home</span>
                        <span class="font-label-md text-label-md">Beranda</span>
                    </a>
                @endif
            @endauth
        </div>
        <div class="p-md border-t border-outline-variant flex flex-col gap-sm">
            <!-- Theme Toggle Desktop -->
            <div class="flex items-center justify-between px-md py-sm mb-2 rounded-lg bg-surface-container-high border border-outline-variant/50">
                <span class="font-label-md text-label-md text-on-surface-variant">Theme</span>
                <button onclick="toggleTheme()" class="text-on-surface hover:text-primary transition-colors flex items-center justify-center bg-surface-container-highest p-1 rounded">
                    <span class="material-symbols-outlined text-[18px]">dark_mode</span>
                </button>
            </div>
            @auth
            <div class="flex items-center gap-md px-md py-sm mb-2">
                <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="font-label-md text-on-surface truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-on-surface-variant truncate">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-md px-md py-sm cursor-pointer transition-all text-on-surface-variant hover:bg-surface-container-high rounded-lg text-left">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-md text-label-md">Logout</span>
                </button>
            </form>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 lg:ml-[260px] p-0 lg:p-lg w-full max-w-[1440px] mx-auto min-h-screen overflow-x-hidden">
        <div class="p-3 sm:p-4 lg:p-0">
            {{ $slot }}
        </div>
    </main>

    <!-- BottomNavBar Mobile -->
    <nav class="lg:hidden fixed bottom-0 left-0 w-full z-40 bg-surface border-t border-outline-variant shadow-lg">
        @auth
            @if(auth()->user()->role === 'qa')
                <div class="flex overflow-x-auto no-scrollbar h-16 items-center">
                    <a href="{{ route('qa.dashboard') }}" wire:navigate class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.dashboard') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('qa.dashboard') ? 'filled-icon' : '' }}">dashboard</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Dashboard</span>
                    </a>
                    <a href="{{ route('qa.rekap') }}" wire:navigate class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.rekap') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('qa.rekap') ? 'filled-icon' : '' }}">calendar_month</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Rekap</span>
                    </a>
                    <a href="{{ route('qa.master.karyawan') }}" wire:navigate class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.master.karyawan') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px]">group</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Karyawan</span>
                    </a>
                    <a href="{{ route('qa.master.departemen') }}" wire:navigate class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.master.departemen') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px]">domain</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Departemen</span>
                    </a>
                    <a href="{{ route('qa.master.klausul') }}" wire:navigate class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.master.klausul') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px]">rule</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Klausul</span>
                    </a>
                    <a href="{{ route('qa.master.akun') }}" wire:navigate class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.master.akun') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px]">manage_accounts</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Akun</span>
                    </a>
                </div>
            @else
                <div class="flex justify-around items-center h-16">
                    <a href="{{ route('beranda') }}" wire:navigate class="flex flex-col items-center justify-center min-w-[72px] h-full px-1 {{ request()->routeIs('beranda') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('beranda') ? 'filled-icon' : '' }}">home</span>
                        <span class="text-[9px] mt-0.5 font-semibold">Beranda</span>
                    </a>
                </div>
            @endif
        @endauth
    </nav>

    @livewireScripts
</body>
</html>
