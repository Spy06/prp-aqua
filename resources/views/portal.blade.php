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
        body {
            background: radial-gradient(circle at 50% 30%, #ffffff 0%, #f1f5f9 100%) !important;
        }
        html.dark body {
            background: radial-gradient(circle at 50% 30%, #0b0f19 0%, #020617 100%) !important;
        }

        .font-hanken { font-family: 'Hanken Grotesk', sans-serif; }
        .font-inter { font-family: 'Inter', sans-serif; }

        .glass-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html.dark .glass-panel {
            background: #0f172a;
            border: 1px solid #1e293b;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -4px rgba(0, 0, 0, 0.2);
        }

        .system-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-width: 2px;
            border-color: transparent;
        }
        .system-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.12);
            border-color: rgba(59, 130, 246, 0.5) !important;
        }
        .system-card .card-wave {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .system-card:hover .card-wave {
            transform: scale(1.1) translate(4px, -4px);
            opacity: 0.9;
        }

        .btn-portal-blue {
            background-color: #0d47a1;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.25);
            transition: all 0.3s ease;
        }
        .btn-portal-blue:hover {
            background-color: #0b3c8f;
            box-shadow: 0 6px 16px rgba(13, 71, 161, 0.35);
            transform: translateY(-1px);
        }
        .btn-portal-blue:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body class="font-inter text-slate-800 dark:text-slate-100 min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- Background Intersecting Wave Ribbons -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <svg class="absolute inset-0 w-full h-full opacity-60" viewBox="0 0 1440 800" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <!-- Ribbon 1 -->
            <path d="M-100,300 C300,150 600,600 1000,250 C1200,100 1400,400 1600,300" stroke="url(#portal-grad-1)" stroke-width="48" stroke-linecap="round" opacity="0.12"/>
            <!-- Ribbon 2 -->
            <path d="M-100,220 C200,350 700,100 1100,350 C1300,450 1400,200 1600,280" stroke="url(#portal-grad-2)" stroke-width="32" stroke-linecap="round" opacity="0.15"/>
            <!-- Ribbon 3 -->
            <path d="M-100,380 C400,200 800,550 1200,200 C1350,100 1500,300 1600,220" stroke="url(#portal-grad-3)" stroke-width="16" stroke-linecap="round" opacity="0.18"/>
            
            <defs>
                <linearGradient id="portal-grad-1" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#06b6d4" />
                    <stop offset="50%" stop-color="#3b82f6" />
                    <stop offset="100%" stop-color="#0284c7" />
                </linearGradient>
                <linearGradient id="portal-grad-2" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#3b82f6" />
                    <stop offset="50%" stop-color="#00f2fe" />
                    <stop offset="100%" stop-color="#4f46e5" />
                </linearGradient>
                <linearGradient id="portal-grad-3" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#00f2fe" />
                    <stop offset="100%" stop-color="#3b82f6" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    <!-- Top Header Navigation -->
    <header class="relative z-10 w-full border-b border-slate-200/80 dark:border-slate-800/80 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md px-6 sm:px-12 py-4">
        <div class="w-full flex items-center justify-between">
            <div class="flex items-center">
                <img src="{{ asset('images/aqua-logo.png') }}" onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/id/3/36/AQUA_Logo_2013.png';" alt="AQUA Logo" class="h-10 w-auto object-contain drop-shadow-md">
                
                <!-- Vertical Divider -->
                <div class="h-8 w-[1px] bg-slate-300 dark:bg-slate-700 mx-4"></div>

                <div>
                    <h1 class="font-hanken font-extrabold text-slate-900 dark:text-white text-base sm:text-lg leading-tight tracking-tight">
                        PT TIRTA INVESTAMA
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Plant Cianjur — Danone Aqua</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 px-4 py-2 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>2 Sistem Aktif</span>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-12">
        <div class="max-w-5xl w-full mx-auto flex flex-col items-center gap-10">

            <!-- Title & Welcome Header -->
            <div class="text-center max-w-2xl space-y-3">
                <h2 class="font-hanken text-3xl sm:text-4xl font-extrabold text-blue-900 dark:text-blue-400 tracking-tight leading-tight">
                    Pilih Sistem Informasi
                </h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 font-medium">
                    Silakan pilih sistem informasi yang ingin Anda akses untuk melanjutkan ke halaman login masing-masing sistem.
                </p>
            </div>

            <!-- Cards Selector Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 w-full max-w-4xl">

                <!-- System 1: SIVERA -->
                <div class="system-card glass-panel rounded-2xl p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden group">
                    <!-- Top-Right Card Wave Graphic -->
                    <div class="absolute top-0 right-0 w-48 h-32 pointer-events-none overflow-hidden card-wave opacity-70">
                        <svg class="w-full h-full" viewBox="0 0 200 150" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                            <path d="M50,0 C110,35 150,15 200,85 L200,0 Z" fill="url(#card-wave-grad-sivera)"/>
                            <defs>
                                <linearGradient id="card-wave-grad-sivera" x1="100%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#93c5fd" stop-opacity="0.5"/>
                                    <stop offset="100%" stop-color="#dbeafe" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-all"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-[#0d47a1] text-white flex items-center justify-center shadow-lg shadow-blue-900/20">
                                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                            </div>
                            <div>
                                <h3 class="font-hanken text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white leading-tight">
                                    SIVERA
                                </h3>
                                <p class="text-xs font-bold text-[#0d47a1] dark:text-blue-400 uppercase tracking-wide">
                                    SISTEM VERIFIKASI PRP PLANT CIANJUR
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6">
                            Sistem verifikasi, pelaporan temuan Prerequisite Program (PRP), pemantauan tindak lanjut PIC, rekapitulasi data, dan verifikasi tim QA Plant Cianjur.
                        </p>

                        <div class="flex flex-wrap gap-2 mb-8">
                            <span class="text-xs px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-850 text-slate-700 dark:text-slate-200 font-medium inline-flex items-center gap-1.5 border border-slate-200/50 dark:border-slate-700/50">
                                <span class="material-symbols-outlined text-base text-slate-500 dark:text-slate-400">description</span> Lapor Temuan
                            </span>
                            <span class="text-xs px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-850 text-slate-700 dark:text-slate-200 font-medium inline-flex items-center gap-1.5 border border-slate-200/50 dark:border-slate-700/50">
                                <span class="material-symbols-outlined text-base text-slate-500 dark:text-slate-400">edit_note</span> Tindak Lanjut PIC
                            </span>
                            <span class="text-xs px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-850 text-slate-700 dark:text-slate-200 font-medium inline-flex items-center gap-1.5 border border-slate-200/50 dark:border-slate-700/50">
                                <span class="material-symbols-outlined text-base text-slate-500 dark:text-slate-400">check_circle</span> Verifikasi QA
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('login', ['system' => 'sivera']) }}" class="btn-portal-blue w-full py-3.5 px-6 rounded-xl flex items-center justify-center gap-2 relative z-10">
                        <span>Masuk ke SIVERA</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>

                <!-- System 2: BOS'QU -->
                <div class="system-card glass-panel rounded-2xl p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden group">
                    <!-- Top-Right Card Wave Graphic -->
                    <div class="absolute top-0 right-0 w-48 h-32 pointer-events-none overflow-hidden card-wave opacity-70">
                        <svg class="w-full h-full" viewBox="0 0 200 150" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                            <path d="M50,0 C110,35 150,15 200,85 L200,0 Z" fill="url(#card-wave-grad-bosq)"/>
                            <defs>
                                <linearGradient id="card-wave-grad-bosq" x1="100%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#93c5fd" stop-opacity="0.5"/>
                                    <stop offset="100%" stop-color="#dbeafe" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-all"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-[#0d47a1] text-white flex items-center justify-center shadow-lg shadow-blue-900/20">
                                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">visibility</span>
                            </div>
                            <div>
                                <h3 class="font-hanken text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white leading-tight">
                                    BOS'QU
                                </h3>
                                <p class="text-xs font-bold text-[#0d47a1] dark:text-blue-400 uppercase tracking-wide">
                                    BEHAVIOR OBSERVATION SYSTEM QUALITY
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6">
                            BOS'Q dilakukan untuk meningkatkan budaya quality dengan membangun kesadaran mutu, mendorong kepatuhan terhadap standar, dan memperkuat komitmen terhadap perbaikan berkelanjutan di seluruh lini kerja.  
                        </p>

                        <div class="flex flex-wrap gap-2 mb-8">
                            <span class="text-xs px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-850 text-slate-700 dark:text-slate-200 font-medium inline-flex items-center gap-1.5 border border-slate-200/50 dark:border-slate-700/50">
                                <span class="material-symbols-outlined text-base text-slate-500 dark:text-slate-400">person</span> Lapor Observasi
                            </span>
                            <span class="text-xs px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-medium inline-flex items-center gap-1.5 border border-slate-200/60 dark:border-slate-700/60">
                                <span class="material-symbols-outlined text-base text-slate-500 dark:text-slate-400">edit_note</span> Tindak Lanjut
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('login', ['system' => 'bosq']) }}" class="btn-portal-blue w-full py-3.5 px-6 rounded-xl flex items-center justify-center gap-2 relative z-10">
                        <span>Masuk ke BOS'QU</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-200/80 dark:border-slate-800/80 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md px-6 py-4 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            © 2026 PT Tirta Investama — Plant Cianjur. <a href="https://github.com/FahriID563" target="_blank" rel="noopener noreferrer" class="hover:underline opacity-80 transition-opacity">Built by @FahriID563</a>. All Rights Reserved.
        </p>
    </footer>

    @fluxScripts
</body>
</html>
