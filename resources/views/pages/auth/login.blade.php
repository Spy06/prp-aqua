<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $system = request('system', 'sivera');
        
        $systemConfig = match($system) {
            'bosq' => [
                'title'    => "BOS'Q",
                'sub'      => 'Behavior Observation System Quality',
                'icon'     => 'visibility',
                'bg_icon'  => 'bg-cyan-100 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-400 dark:border-cyan-500/30',
                'title_cls'=> 'text-cyan-800 dark:text-cyan-300',
                'btn_cls'  => 'bg-cyan-600 hover:bg-cyan-700 dark:bg-cyan-500 dark:hover:bg-cyan-400 text-white dark:text-cyan-950 shadow-cyan-600/20',
            ],
            default => [
                'title'    => 'SIVERA',
                'sub'      => 'Sistem Verifikasi PRP Plant',
                'icon'     => 'verified_user',
                'bg_icon'  => 'bg-cyan-100 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-400 dark:border-cyan-500/30',
                'title_cls'=> 'text-cyan-800 dark:text-cyan-300',
                'btn_cls'  => 'bg-cyan-600 hover:bg-cyan-700 dark:bg-cyan-500 dark:hover:bg-cyan-400 text-white dark:text-cyan-950 shadow-cyan-600/20',
            ],
        };
    @endphp
    <title>{{ $systemConfig['title'] }} - Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Hanken+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <style>
        .glass-card {
            background: rgba(244, 250, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        html.dark .glass-card {
            background: rgba(24, 33, 39, 0.85);
            border: 1px solid rgba(60, 73, 78, 0.5);
        }

        .bg-light-image {
            background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAa8C3sUdYvbGDqsogBBjmpEiU5z4jc-amDezSIfByzPo-pvCR6qXXMMo2hFDZzqwDgyPzNN8sDzEJYsBDq6T610v3iXMKqv0odh_Z-MA8q2tsCyblUZphXAdl0v6WPeNJkc-1wgSLcTwhTpNo1jSZdZL2WorvnOKXWJCFvUFeEBvz5lQtVshDGW5GUUeyaqdmlKjRaJV6adKp6gru_S1n2L2A7oQ-YV6_rFfCjcrr1A3WNeMRVjjBu');
        }

        html.dark .bg-dark-image {
            background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAMfK9yV7VsAmuw6XGtzmh7CI4aomIGZHqUFRSxFa-9LZ8cDEv8iQBBwslCjqqDw5qwM6mda2zY8oCZ1WABoTn905T63_Bpc3ufwhCxznZdomg-lDeiU8OGRRlibIFeNLXghn5f_B6OisKljemdssh3GszW-CzkVLKP3j7feeHMQ0xALVLMj7ygceRN7zkV3AAWHQqWj5WYO9SpDuolfXChS6qfLRMTnQ36j_bjtOGffNZB-FdRKzlT');
        }

        .font-hanken {
            font-family: 'Hanken Grotesk', sans-serif;
        }

        .font-inter {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body
    class="font-inter text-zinc-900 dark:text-zinc-100 min-h-screen w-screen flex flex-col items-center justify-center overflow-x-hidden relative py-8">

    <!-- Background Image -->
    <div class="absolute inset-0 z-0 bg-cover bg-center bg-light-image dark:bg-dark-image transition-all duration-500">
        <div class="absolute inset-0 bg-blue-900/25 dark:bg-zinc-950/85 transition-all duration-500"></div>
    </div>

    <!-- Login Card Container -->
    <div class="relative z-10 w-full max-w-[480px] p-4 sm:p-6">

        <!-- Tombol Kembali ke Pemilihan Sistem -->
        <div class="mb-3">
            <a href="{{ route('portal') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md text-xs font-bold text-zinc-700 dark:text-zinc-200 border border-white/60 dark:border-zinc-700/60 hover:bg-white dark:hover:bg-zinc-800 transition-all shadow-md">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                <span>Kembali ke Pemilihan Sistem</span>
            </a>
        </div>

        <div class="glass-card rounded-2xl p-6 sm:p-10 shadow-2xl flex flex-col gap-6 items-center">

            <!-- Branding Header -->
            <div class="flex flex-col items-center gap-2 mb-2 w-full">
                <div
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full {{ $systemConfig['bg_icon'] }} flex items-center justify-center shadow-lg dark:border mb-2 transition-all">
                    <span class="material-symbols-outlined text-4xl sm:text-5xl"
                        style="font-variation-settings: 'FILL' 1;">{{ $systemConfig['icon'] }}</span>
                </div>
                <h1
                    class="font-hanken text-2xl sm:text-3xl font-bold {{ $systemConfig['title_cls'] }} text-center tracking-tight transition-colors">
                    {{ $systemConfig['title'] }}
                </h1>
                <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-400 text-center transition-colors">
                    {{ $systemConfig['sub'] }}
                </p>
            </div>

            <!-- Error handling -->
            @if(session('status'))
                <div
                    class="w-full text-center text-sm font-medium text-green-600 dark:text-green-400 bg-green-50/50 dark:bg-green-900/20 py-2 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div
                    class="w-full text-center text-sm font-medium text-red-600 dark:text-red-400 bg-red-50/50 dark:bg-red-900/20 py-2 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.store') }}" class="w-full flex flex-col gap-5">
                @csrf
                <input type="hidden" name="system" value="{{ $system }}">

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-widest"
                        for="name">Nama Karyawan</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 dark:text-cyan-500/60 z-10 pointer-events-none text-xl">person</span>
                        <input
                            class="w-full h-12 sm:h-14 pl-11 pr-4 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white/50 dark:bg-zinc-900/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-zinc-900 dark:text-zinc-100 transition-all text-sm sm:text-base placeholder:text-zinc-400"
                            id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap" required
                            autofocus type="text" />
                    </div>
                </div>

                <div class="flex flex-col gap-2" x-data="{ showPassword: false }">
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-widest"
                        for="password">Password</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 dark:text-cyan-500/60 z-10 pointer-events-none text-xl">lock</span>
                        <input type="password" :type="showPassword ? 'text' : 'password'"
                            class="w-full h-12 sm:h-14 pl-11 pr-12 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white/50 dark:bg-zinc-900/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-zinc-900 dark:text-zinc-100 transition-all text-sm sm:text-base placeholder:text-zinc-400"
                            id="password" name="password" placeholder="••••••••" required />
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-cyan-500 transition-colors focus:outline-none flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl"
                                x-text="showPassword ? 'visibility' : 'visibility_off'"></span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between w-full mt-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input
                            class="rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-cyan-600 dark:text-cyan-400 focus:ring-cyan-500 w-4 h-4"
                            type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                        <span
                            class="text-sm text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-200 transition-colors">Ingat
                            saya</span>
                    </label>
                </div>

                <button
                    class="w-full h-12 sm:h-14 mt-2 {{ $systemConfig['btn_cls'] }} rounded-xl font-bold text-base sm:text-lg transition-all shadow-lg active:scale-[0.98] flex items-center justify-center gap-3"
                    type="submit">
                    <span>Masuk ke {{ $systemConfig['title'] }}</span>
                    <span class="material-symbols-outlined text-xl sm:text-2xl">login</span>
                </button>
            </form>

            <!-- Footer Note -->
            <div class="mt-2 border-t border-zinc-200/50 dark:border-zinc-700/50 w-full pt-4 text-center">
                <p class="text-xs text-zinc-500 dark:text-zinc-500">
                    © 2026 PT Tirta Investama — Plant Cianjur
                </p>
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>