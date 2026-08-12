<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $system = request('system', 'sivera');
        
        $systemConfig = match($system) {
            'bosq' => [
                'title'    => "BOS'QU",
                'sub'      => 'Behavior Observation System Quality',
                'btn_cls'  => 'btn-theme-bosq',
                'title_cls'=> 'text-blue-900 dark:text-blue-400',
            ],
            default => [
                'title'    => 'SIVERA',
                'sub'      => 'Sistem Verifikasi PRP Plant Cianjur',
                'btn_cls'  => 'btn-theme-sivera',
                'title_cls'=> 'text-indigo-900 dark:text-indigo-400',
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
        body {
            background: radial-gradient(circle at 50% 30%, #ffffff 0%, #f1f5f9 100%) !important;
        }
        html.dark body {
            background: radial-gradient(circle at 50% 30%, #0b0f19 0%, #020617 100%) !important;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.03), 0 0 40px rgba(0,0,0,0.02);
        }

        html.dark .login-card {
            background: #0f172a;
            border: 1px solid #1e293b;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        .btn-theme-bosq {
            background: linear-gradient(135deg, #1976d2 0%, #2196f3 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(25, 118, 210, 0.3);
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-theme-bosq:hover {
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.45);
            transform: translateY(-1px);
        }
        .btn-theme-bosq:active {
            transform: scale(0.98);
        }

        .btn-theme-sivera {
            background: linear-gradient(135deg, #7c4dff 0%, #673ab7 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(103, 58, 183, 0.3);
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-theme-sivera:hover {
            box-shadow: 0 6px 20px rgba(103, 58, 183, 0.45);
            transform: translateY(-1px);
        }
        .btn-theme-sivera:active {
            transform: scale(0.98);
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

    <!-- Background Intersecting Wave Ribbons -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <svg class="absolute bottom-0 left-0 w-full h-[280px] sm:h-[350px] opacity-80" viewBox="0 0 1440 250" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <!-- Ribbon 1 -->
            <path d="M-100,160 C300,60 500,240 900,100 C1200,0 1400,200 1600,120" stroke="url(#wave-grad-{{ $system }})" stroke-width="24" stroke-linecap="round" opacity="0.25"/>
            <!-- Ribbon 2 -->
            <path d="M-100,110 C200,210 600,50 1000,180 C1300,280 1400,60 1600,140" stroke="url(#wave-grad-{{ $system }})" stroke-width="16" stroke-linecap="round" opacity="0.25"/>
            <!-- Ribbon 3 -->
            <path d="M-100,210 C400,110 700,280 1100,80 C1300,-10 1500,180 1600,90" stroke="url(#wave-grad-{{ $system }})" stroke-width="8" stroke-linecap="round" opacity="0.3"/>
            
            <defs>
                @if($system === 'bosq')
                    <linearGradient id="wave-grad-bosq" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#06b6d4" />
                        <stop offset="50%" stop-color="#3b82f6" />
                        <stop offset="100%" stop-color="#0284c7" />
                    </linearGradient>
                @else
                    <linearGradient id="wave-grad-sivera" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#7c4dff" />
                        <stop offset="50%" stop-color="#673ab7" />
                        <stop offset="100%" stop-color="#3f51b5" />
                    </linearGradient>
                @endif
            </defs>
        </svg>
    </div>

    <!-- Login Card Container -->
    <div class="relative z-10 w-full max-w-[480px] p-4 sm:p-6">

        <!-- Tombol Kembali ke Pemilihan Sistem -->
        <div class="mb-3">
            <a href="{{ route('portal') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-zinc-900 text-xs font-bold text-zinc-700 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all shadow-md">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                <span>Kembali ke Pemilihan Sistem</span>
            </a>
        </div>

        <div class="login-card rounded-2xl p-6 sm:p-10 flex flex-col gap-6 items-center">

            <!-- Branding Header -->
            <div class="flex flex-col items-center gap-2 mb-2 w-full">
                <!-- Aqua Logo -->
                <img src="{{ asset('images/aqua-logo.png') }}" onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/id/3/36/AQUA_Logo_2013.png';" alt="AQUA Logo" class="h-16 w-auto object-contain mb-3 drop-shadow-sm">
                
                <h1 class="font-hanken text-2xl sm:text-3.5xl font-extrabold {{ $systemConfig['title_cls'] }} text-center tracking-tight transition-colors leading-none">
                    {{ $systemConfig['title'] }}
                </h1>
                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 text-center font-medium mt-1">
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
                    <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300"
                        for="name">Nama Karyawan</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 dark:text-zinc-500 z-10 pointer-events-none text-xl">person</span>
                        <input
                            class="w-full h-12 sm:h-14 pl-11 pr-4 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white/50 dark:bg-zinc-900/50 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-zinc-900 dark:text-zinc-100 transition-all text-sm sm:text-base placeholder:text-zinc-400"
                            id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap" required
                            autofocus type="text" />
                    </div>
                </div>

                <div class="flex flex-col gap-2" x-data="{ showPassword: false }">
                    <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300"
                        for="password">Password</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 dark:text-zinc-500 z-10 pointer-events-none text-xl">lock</span>
                        <input :type="showPassword ? 'text' : 'password'"
                            class="w-full h-12 sm:h-14 pl-11 pr-12 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white/50 dark:bg-zinc-900/50 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-zinc-900 dark:text-zinc-100 transition-all text-sm sm:text-base placeholder:text-zinc-400"
                            id="password" name="password" placeholder="Masukkan Password" required />
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-blue-500 transition-colors focus:outline-none flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl"
                                x-text="showPassword ? 'visibility' : 'visibility_off'"></span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between w-full mt-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input
                            class="rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-blue-600 dark:text-blue-400 focus:ring-blue-500 w-4 h-4"
                            type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                        <span
                            class="text-sm text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-200 transition-colors">Ingat
                            saya</span>
                    </label>
                </div>

                <button
                    class="w-full h-12 sm:h-14 mt-2 {{ $systemConfig['btn_cls'] }} rounded-xl font-bold text-base sm:text-lg transition-all shadow-lg active:scale-[0.98] flex items-center justify-center gap-3"
                    type="submit">
                    <span class="material-symbols-outlined text-xl sm:text-2xl">login</span>
                    <span>Masuk ke {{ $systemConfig['title'] }}</span>
                </button>
            </form>

            <!-- Footer Note -->
            <div class="mt-2 border-t border-zinc-200/50 dark:border-zinc-700/50 w-full pt-4 text-center">
                <p class="text-xs text-zinc-500 dark:text-zinc-500">
                    © 2026 PT Tirta Investama — Plant Cianjur. <a href="https://github.com/FahriID563" target="_blank" rel="noopener noreferrer" class="hover:underline opacity-80 transition-opacity">Built by @FahriID563</a>
                </p>
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>