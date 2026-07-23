<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Sistem Informasi Plant — PT Tirta Investama</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Hanken+Grotesk:wght@400;600;700;800&display=swap" rel="stylesheet" />

    <style>
        .font-hanken { font-family: 'Hanken Grotesk', sans-serif; }
        .font-inter { font-family: 'Inter', sans-serif; }

        .glass-panel {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        html.dark .glass-panel {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .system-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .system-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.14);
        }
    </style>
</head>
<body class="font-inter text-slate-800 dark:text-slate-100 bg-slate-100 dark:bg-slate-950 min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- Background Decoration -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-500/15 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-purple-500/15 rounded-full blur-3xl"></div>
    </div>

    <!-- Top Header Navigation -->
    <header class="relative z-10 w-full border-b border-slate-200/80 dark:border-slate-800/80 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-lg shadow-md">
                    A
                </div>
                <div>
                    <h1 class="font-hanken font-extrabold text-slate-900 dark:text-white text-base sm:text-lg leading-tight tracking-tight">
                        PT TIRTA INVESTAMA
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Plant Cianjur — Danone Aqua</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-200/70 dark:bg-slate-800/70 px-3.5 py-1.5 rounded-full border border-slate-300/60 dark:border-slate-700">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Portal Sistem Aktif</span>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-12">
        <div class="max-w-5xl w-full mx-auto flex flex-col items-center gap-10">

            <!-- Title & Welcome Header -->
            <div class="text-center max-w-2xl space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-1">
                    <span class="material-symbols-outlined text-sm">grid_view</span>
                    Portal Integrasi Sistem Informasi
                </div>
                <h2 class="font-hanken text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Pilih Sistem Informasi
                </h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                    Silakan pilih sistem informasi yang ingin Anda akses untuk melanjutkan ke halaman login masing-masing sistem.
                </p>
            </div>

            <!-- Cards Selector Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 w-full max-w-4xl">

                <!-- System 1: SIVERA -->
                <div class="system-card glass-panel rounded-2xl p-6 sm:p-8 flex flex-col justify-between border-2 border-transparent hover:border-blue-500/50 relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30">
                                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                            </div>
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                SIVERA v2.0
                            </span>
                        </div>

                        <h3 class="font-hanken text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            SIVERA
                        </h3>
                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-3">
                            Sistem Verifikasi PRP Plant
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6">
                            Sistem verifikasi, pelaporan temuan Prerequisite Program (PRP), pemantauan tindak lanjut PIC, rekapitulasi data, dan verifikasi tim QA Plant Cianjur.
                        </p>

                        <div class="flex flex-wrap gap-2 mb-8">
                            <span class="text-xs px-2.5 py-1 rounded-md bg-slate-200/60 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium">📌 Lapor Temuan</span>
                            <span class="text-xs px-2.5 py-1 rounded-md bg-slate-200/60 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium">⚡ Tindak Lanjut PIC</span>
                            <span class="text-xs px-2.5 py-1 rounded-md bg-slate-200/60 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium">🛡️ Verifikasi QA</span>
                        </div>
                    </div>

                    <a href="{{ route('login', ['system' => 'sivera']) }}" class="w-full py-3.5 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm sm:text-base flex items-center justify-center gap-2 shadow-lg shadow-blue-600/25 transition-all group-hover:gap-3">
                        <span>Masuk ke SIVERA</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>

                <!-- System 2: SIM-Plant (Sistem Informasi Operasional) -->
                <div class="system-card glass-panel rounded-2xl p-6 sm:p-8 flex flex-col justify-between border-2 border-transparent hover:border-purple-500/50 relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-purple-600 text-white flex items-center justify-center shadow-lg shadow-purple-600/30">
                                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">analytics</span>
                            </div>
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                SIM-PLANT v1.0
                            </span>
                        </div>

                        <h3 class="font-hanken text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                            SIM-PLANT
                        </h3>
                        <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-3">
                            Sistem Informasi Operasional Plant
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6">
                            Portal sistem informasi operasional, manajemen data pendukung produksi, dan sistem informasi manajemen terpadu Plant Aqua Cianjur.
                        </p>

                        <div class="flex flex-wrap gap-2 mb-8">
                            <span class="text-xs px-2.5 py-1 rounded-md bg-slate-200/60 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium">📊 Data Operasional</span>
                            <span class="text-xs px-2.5 py-1 rounded-md bg-slate-200/60 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium">📋 Monitoring Plant</span>
                            <span class="text-xs px-2.5 py-1 rounded-md bg-slate-200/60 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium">📈 Laporan Performa</span>
                        </div>
                    </div>

                    <a href="{{ route('login', ['system' => 'other']) }}" class="w-full py-3.5 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm sm:text-base flex items-center justify-center gap-2 shadow-lg shadow-purple-600/25 transition-all group-hover:gap-3">
                        <span>Masuk ke SIM-PLANT</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-200/80 dark:border-slate-800/80 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md px-6 py-4 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            © 2026 PT Tirta Investama — Plant Cianjur. All Rights Reserved.
        </p>
    </footer>

    @fluxScripts
</body>
</html>
