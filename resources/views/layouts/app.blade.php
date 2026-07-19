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
            if (document.documentElement.classList.contains('dark')) {
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
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
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

<body
    class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col pb-20 lg:pb-0 lg:flex-row">
    <!-- TopNavBar Mobile -->
    <header
        class="lg:hidden bg-surface flex justify-between items-center w-full px-4 h-14 sticky top-0 z-50 border-b border-outline-variant shadow-sm">
        <div class="flex items-center gap-2 min-w-0">
            <div
                class="w-7 h-7 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container shrink-0">
                <span class="material-symbols-outlined text-base">factory</span>
            </div>
            <h1 class="text-sm font-bold text-primary truncate">Verifikasi PRP</h1>
        </div>
        <div class="flex items-center gap-1">
            <!-- Theme Toggle Mobile -->
            <button onclick="toggleTheme()"
                class="text-on-surface-variant hover:bg-surface-container-low transition-colors rounded-full p-2 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">dark_mode</span>
            </button>
            @auth
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-on-surface-variant hover:bg-surface-container-low transition-colors rounded-full p-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">logout</span>
                    </button>
                </form>
            @endauth
        </div>
    </header>

    <!-- SideNavBar Desktop -->
    <nav
        class="hidden lg:flex flex-col w-[260px] h-screen fixed left-0 top-0 overflow-y-auto bg-surface-container-low border-r border-outline-variant z-40">
        <div class="p-lg border-b border-outline-variant flex items-center gap-md">
            <div
                class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
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
                    <p class="px-md text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2 mt-4">QA
                        Dashboard</p>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.dashboard') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.dashboard') }}" wire:navigate>
                        <span
                            class="material-symbols-outlined {{ request()->routeIs('qa.dashboard') ? 'filled-icon' : '' }}">bar_chart</span>
                        <span class="font-label-md text-label-md">Grafik Temuan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.daftar-temuan') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.daftar-temuan') }}" wire:navigate>
                        <span
                            class="material-symbols-outlined {{ request()->routeIs('qa.daftar-temuan') ? 'filled-icon' : '' }}">list_alt</span>
                        <span class="font-label-md text-label-md">Daftar Temuan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-sm cursor-pointer transition-all {{ request()->routeIs('qa.rekap') ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg"
                        href="{{ route('qa.rekap') }}" wire:navigate>
                        <span
                            class="material-symbols-outlined {{ request()->routeIs('qa.rekap') ? 'filled-icon' : '' }}">calendar_month</span>
                        <span class="font-label-md text-label-md">Rekap Periode</span>
                    </a>

                    <p class="px-md text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2 mt-4">Master
                        Data</p>
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
                        <span
                            class="material-symbols-outlined {{ request()->routeIs('beranda') ? 'filled-icon' : '' }}">home</span>
                        <span class="font-label-md text-label-md">Beranda</span>
                    </a>
                @endif
            @endauth
        </div>
        <div class="p-md border-t border-outline-variant flex flex-col gap-sm">
            <!-- Theme Toggle Desktop -->
            <div
                class="flex items-center justify-between px-md py-sm mb-2 rounded-lg bg-surface-container-high border border-outline-variant/50">
                <span class="font-label-md text-label-md text-on-surface-variant">Theme</span>
                <button onclick="toggleTheme()"
                    class="text-on-surface hover:text-primary transition-colors flex items-center justify-center bg-surface-container-highest p-1 rounded">
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
                    <button type="submit"
                        class="w-full flex items-center gap-md px-md py-sm cursor-pointer transition-all text-on-surface-variant hover:bg-surface-container-high rounded-lg text-left">
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
                    <a href="{{ route('qa.dashboard') }}" wire:navigate
                        class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.dashboard') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span
                            class="material-symbols-outlined text-[22px] {{ request()->routeIs('qa.dashboard') ? 'filled-icon' : '' }}">bar_chart</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Grafik</span>
                    </a>
                    <a href="{{ route('qa.daftar-temuan') }}" wire:navigate
                        class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.daftar-temuan') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span
                            class="material-symbols-outlined text-[22px] {{ request()->routeIs('qa.daftar-temuan') ? 'filled-icon' : '' }}">list_alt</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Daftar</span>
                    </a>
                    <a href="{{ route('qa.rekap') }}" wire:navigate
                        class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.rekap') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span
                            class="material-symbols-outlined text-[22px] {{ request()->routeIs('qa.rekap') ? 'filled-icon' : '' }}">calendar_month</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Rekap</span>
                    </a>
                    <a href="{{ route('qa.master.karyawan') }}" wire:navigate
                        class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.master.karyawan') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px]">group</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Karyawan</span>
                    </a>
                    <a href="{{ route('qa.master.departemen') }}" wire:navigate
                        class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.master.departemen') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px]">domain</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Departemen</span>
                    </a>
                    <a href="{{ route('qa.master.klausul') }}" wire:navigate
                        class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.master.klausul') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px]">rule</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Klausul</span>
                    </a>
                    <a href="{{ route('qa.master.akun') }}" wire:navigate
                        class="flex flex-col items-center justify-center shrink-0 min-w-[72px] h-full px-1 {{ request()->routeIs('qa.master.akun') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span class="material-symbols-outlined text-[22px]">manage_accounts</span>
                        <span class="text-[9px] mt-0.5 font-semibold whitespace-nowrap">Akun</span>
                    </a>
                </div>
            @else
                <div class="flex justify-around items-center h-16">
                    <a href="{{ route('beranda') }}" wire:navigate
                        class="flex flex-col items-center justify-center min-w-[72px] h-full px-1 {{ request()->routeIs('beranda') ? 'text-primary border-t-2 border-primary -mt-px' : 'text-on-surface-variant' }} transition-colors">
                        <span
                            class="material-symbols-outlined text-[22px] {{ request()->routeIs('beranda') ? 'filled-icon' : '' }}">home</span>
                        <span class="text-[9px] mt-0.5 font-semibold">Beranda</span>
                    </a>
                </div>
            @endif
        @endauth
    </nav>

    {{-- Custom Confirmation Dialog --}}
    <dialog id="custom-confirm-modal" class="fixed inset-0 m-auto rounded-xl shadow-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 max-w-[360px] w-[90%] text-zinc-900 dark:text-zinc-100 outline-none select-none backdrop:bg-black/50 backdrop:backdrop-blur-sm">
        <div class="flex items-start gap-3 mb-4">
            <div class="p-2 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-500 shrink-0">
                <span class="material-symbols-outlined text-xl leading-none">help</span>
            </div>
            <div>
                <h3 class="font-semibold text-sm">Konfirmasi</h3>
                <p id="custom-confirm-message" class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">Apakah Anda yakin?</p>
            </div>
        </div>
        <div class="flex justify-end gap-2 text-xs font-semibold">
            <button id="custom-confirm-cancel" class="px-4 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 text-zinc-700 dark:text-zinc-200 transition">
                Batal
            </button>
            <button id="custom-confirm-ok" class="px-4 py-2 rounded-lg bg-primary hover:opacity-90 text-white transition shadow-sm">
                Lanjutkan
            </button>
        </div>
    </dialog>

    <script>
        (function () {
            let pendingTarget = null;
            let bypassing = false;

            // Override window.confirm — called by Alpine/Livewire for wire:confirm
            window.confirm = function (message) {
                if (bypassing) return true; // bypass mode: let Livewire proceed

                const dialog = document.getElementById('custom-confirm-modal');
                const msgEl  = document.getElementById('custom-confirm-message');
                if (dialog && msgEl) {
                    msgEl.textContent = message || 'Apakah Anda yakin?';
                    dialog.showModal();
                }
                return false; // block Livewire action — wait for user click
            };

            // Capture the target element before Alpine processes the click
            document.addEventListener('click', function (e) {
                if (bypassing) return;
                const t = e.target.closest('[wire\\:confirm]');
                if (t) pendingTarget = t;
            }, true);

            function attachButtons() {
                const dialog    = document.getElementById('custom-confirm-modal');
                const btnCancel = document.getElementById('custom-confirm-cancel');
                const btnOk     = document.getElementById('custom-confirm-ok');
                if (!dialog || !btnCancel || !btnOk) return;

                btnCancel.onclick = function () {
                    dialog.close();
                    pendingTarget = null;
                };

                btnOk.onclick = function () {
                    dialog.close();
                    if (!pendingTarget) return;
                    var el = pendingTarget;
                    pendingTarget = null;
                    bypassing = true;       // next window.confirm call → return true
                    el.click();             // re-trigger → Alpine calls confirm() → true → action runs
                    setTimeout(function () { bypassing = false; }, 200);
                };
            }

            document.addEventListener('DOMContentLoaded', attachButtons);
            document.addEventListener('livewire:navigated', attachButtons);
        })();
    </script>

    @livewireScripts
</body>

</html>