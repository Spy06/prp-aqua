<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else { document.documentElement.classList.remove('dark'); }
        document.addEventListener('livewire:navigated', () => {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else { document.documentElement.classList.remove('dark'); }
        });
    </script>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $title ?? 'QA — SIVERA' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
    <style>
        :root {
            --bp: #2196f3;       /* primary main (blue) */
            --bp-light: #e3f2fd; /* primary light */
            --bp-dark: #1e88e5;  /* primary dark */
            
            --bs: #673ab7;       /* secondary main (purple) */
            --bs-light: #ede7f6; /* secondary light */
            --bs-dark: #5e35b1;  /* secondary dark */
            
            --bsur: #eef2f6;     /* body background */
            --bcard: #ffffff;    /* card / paper background */
            --bside: #ffffff;    /* sidebar background */
            --bbor: #e3e8ef;     /* border / divider */
            
            --btxt: #364152;     /* text primary */
            --btxt2: #697586;    /* text secondary */
            
            --success: #00c853;
            --success-light: #b9f6ca;
            --warning: #ffc107;
            --warning-light: #fff8e1;
            --error: #d84315;
            --error-light: #fbe9e7;
        }
        .dark {
            --bsur: #1a223f;     /* darkBackground */
            --bcard: #111936;    /* darkPaper */
            --bside: #111936;    /* darkPaper */
            --bbor: #212946;     /* darkLevel2 */
            
            --btxt: #bdc8f0;     /* darkTextPrimary */
            --btxt2: #8492c4;    /* darkTextSecondary */
            
            --bp-light: rgba(33, 150, 243, 0.15);
            --bs-light: rgba(124, 77, 255, 0.15);
        }
        
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bsur); color: var(--btxt); margin: 0; min-height: 100vh; overflow-x: hidden; }

        /* ── Header App Bar ── */
        .qtop {
            background: var(--bcard);
            border-bottom: 1px solid var(--bbor);
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 40;
            box-shadow: 0 1px 10px rgba(0,0,0,.03);
            transition: left 0.3s ease;
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 240px;
            flex-shrink: 0;
        }
        
        .logo-box {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #7c4dff, #673ab7);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 10px rgba(103, 58, 183, 0.35);
        }
        .logo-box span { color: #fff; font-size: 20px; }
        .logo-text h1 { font-size: 16px; font-weight: 700; color: var(--bs); letter-spacing: -0.3px; margin: 0; }
        .dark .logo-text h1 { color: #b39ddb; }
        .logo-text p { font-size: 10px; color: var(--btxt2); margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Toggle Menu Button (Round-square avatar styling) */
        .qtop-toggle {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: var(--bs-light);
            color: var(--bs-dark);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease-in-out;
            border: none; outline: none;
        }
        .qtop-toggle:hover {
            background: var(--bs-dark);
            color: #fff;
        }
        .dark .qtop-toggle {
            color: #b39ddb;
        }
        .dark .qtop-toggle:hover {
            background: var(--bs);
            color: #fff;
        }

        /* Search Section */
        .qtop-search {
            display: flex; align-items: center;
            background: var(--bsur);
            border-radius: 12px;
            padding: 8px 14px;
            gap: 8px;
            margin-left: 20px;
            width: 100%; max-width: 300px;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .qtop-search:focus-within {
            background: var(--bcard);
            border-color: var(--bp);
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.15);
        }
        .qtop-search input {
            background: transparent; border: none; outline: none;
            color: var(--btxt); font-size: 13.5px; width: 100%;
            font-family: inherit;
        }
        .qtop-search span { color: var(--btxt2); font-size: 18px; }

        /* Topbar Actions */
        .qtop-act { display: flex; align-items: center; gap: 12px; }
        .qtop-icon-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: var(--bp-light);
            color: var(--bp-dark);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
            border: none; outline: none;
        }
        .qtop-icon-btn:hover {
            background: var(--bp-dark);
            color: #fff;
        }
        
        /* User Profile Pill */
        .qtop-profile-pill {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 12px 6px 6px;
            background: var(--bs-light);
            border-radius: 24px;
            border: none; cursor: pointer;
            transition: all 0.2s ease-in-out;
            color: var(--bs-dark);
            font-family: inherit;
        }
        .qtop-profile-pill:hover {
            background: var(--bs-dark);
            color: #fff;
        }
        .qtop-profile-pill:hover .qs-av {
            background: #fff;
            color: var(--bs-dark);
        }
        .qtop-profile-pill .qs-av {
            width: 28px; height: 28px;
            background: var(--bs);
            color: #fff;
            font-weight: 700; font-size: 12px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .qtop-profile-pill .name {
            font-size: 13px; font-weight: 600;
        }

        /* ── Sidebar Drawer ── */
        .qs {
            width: 260px;
            background: var(--bside);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
            border-right: 1px solid var(--bbor);
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .qs-header {
            height: 80px;
            display: flex; align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid var(--bbor);
            flex-shrink: 0;
        }
        .qs-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px;
            scrollbar-width: thin;
            scrollbar-color: rgba(0,0,0,0.1) transparent;
        }
        .qs-content::-webkit-scrollbar { width: 3px; }
        .qs-content::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 4px; }
        .dark .qs-content::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); }
        
        .qs-group-label {
            font-size: 11px; font-weight: 700;
            color: var(--btxt);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 12px 12px 6px;
            display: block;
            opacity: 0.85;
        }
        .qs-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--btxt);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 4px;
        }
        .qs-item:hover {
            background: var(--bs-light);
            color: var(--bs-dark);
        }
        .qs-item.active {
            background: var(--bs-light);
            color: var(--bs-dark);
            font-weight: 600;
        }
        .qs-item span.ic { font-size: 20px; width: 22px; text-align: center; }
        
        .qs-footer {
            padding: 16px;
            border-top: 1px solid var(--bbor);
            background: var(--bsur);
            flex-shrink: 0;
        }
        .qs-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; background: var(--bcard); border-radius: 12px;
            border: 1px solid var(--bbor);
            margin-bottom: 8px;
        }
        .qs-footer .qs-action {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: var(--btxt2); font-size: 13px; font-weight: 600;
            transition: all 0.2s; cursor: pointer; width: 100%;
            background: none; border: none; text-align: left; font-family: inherit;
        }
        .qs-footer .qs-action:hover {
            background: var(--error-light);
            color: var(--error);
        }

        /* ── Main Layout Spacing ── */
        .qmain-wrapper {
            min-height: 100vh;
            padding-top: 80px;
            transition: margin-left 0.3s ease;
        }
        .qcontent-container {
            padding: 24px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* ── Sidebar Open/Closed States ── */
        .sidebar-open .qs { transform: translateX(0); }
        .sidebar-open .qtop { left: 260px; }
        .sidebar-open .qmain-wrapper { margin-left: 260px; }

        .sidebar-closed .qs { transform: translateX(-260px); }
        .sidebar-closed .qtop { left: 0; }
        .sidebar-closed .qmain-wrapper { margin-left: 0; }

        /* Mobile Breakpoints */
        @media (max-width: 960px) {
            .qtop { left: 0 !important; }
            .qmain-wrapper { margin-left: 0 !important; }
            
            .sidebar-open .qs { transform: translateX(0); }
            .sidebar-closed .qs { transform: translateX(-260px); }
            
            .qtop-search { display: none; } /* Hide search on mobile */
        }

        /* ── Earning & Order Card Abstract Circle Decorations ── */
        .earning-card {
            background: linear-gradient(135deg, var(--bs-dark) 0%, var(--bs) 100%);
            color: #fff !important;
            position: relative;
            overflow: hidden;
            border: none !important;
            border-radius: 12px;
        }
        .earning-card::after {
            content: "";
            position: absolute;
            width: 210px; height: 210px;
            background: var(--bs-dark);
            border-radius: 50%;
            top: -85px; right: -95px;
            opacity: 0.5;
        }
        .earning-card::before {
            content: "";
            position: absolute;
            width: 210px; height: 210px;
            background: var(--bs-dark);
            border-radius: 50%;
            top: -125px; right: -15px;
            opacity: 0.25;
        }
        
        .blue-card {
            background: linear-gradient(135deg, var(--bp-dark) 0%, var(--bp) 100%);
            color: #fff !important;
            position: relative;
            overflow: hidden;
            border: none !important;
            border-radius: 12px;
        }
        .blue-card::after {
            content: "";
            position: absolute;
            width: 210px; height: 210px;
            background: var(--bp-dark);
            border-radius: 50%;
            top: -85px; right: -95px;
            opacity: 0.5;
        }
        .blue-card::before {
            content: "";
            position: absolute;
            width: 210px; height: 210px;
            background: var(--bp-dark);
            border-radius: 50%;
            top: -125px; right: -15px;
            opacity: 0.25;
        }

        /* ── Standard Berry Cards ── */
        .bcard {
            background: var(--bcard);
            border: 1px solid var(--bbor);
            border-radius: 12px;
            box-shadow: 0 2px 14px 0 rgba(32, 40, 45, 0.06);
            transition: box-shadow 0.25s, transform 0.25s;
        }
        .bcard:hover {
            box-shadow: 0 6px 20px rgba(94, 53, 177, 0.08);
            transform: translateY(-1px);
        }

        /* ── Stat cards ── */
        .bstat {
            background: var(--bcard);
            border: 1px solid var(--bbor);
            border-radius: 12px;
            padding: 20px;
            display: flex; align-items: center; gap: 16px;
            box-shadow: 0 2px 14px 0 rgba(32, 40, 45, 0.04);
            transition: all 0.25s;
        }
        .bstat:hover {
            box-shadow: 0 6px 20px rgba(103, 58, 183, 0.1);
            transform: translateY(-2px);
        }
        .bstat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .bstat-val { font-size: 26px; font-weight: 700; color: var(--btxt); line-height: 1; }
        .bstat-lbl { font-size: 12.5px; color: var(--btxt2); margin-top: 3px; font-weight: 500; }

        /* ── Berry Table ── */
        .btbl { width: 100%; border-collapse: collapse; }
        .btbl thead tr { background: var(--bp-light); }
        .dark .btbl thead tr { background: rgba(33, 150, 243, 0.12); }
        .btbl th {
            padding: 14px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--bp-dark);
            text-transform: uppercase;
            letter-spacing: .8px;
            white-space: nowrap;
            border-bottom: 1px solid var(--bbor);
        }
        .dark .btbl th { color: #90caf9; }
        .btbl tbody tr { border-bottom: 1px solid var(--bbor); transition: background .15s; }
        .btbl tbody tr:last-child { border-bottom: none; }
        .btbl tbody tr:hover { background: rgba(103, 58, 183, 0.03); }
        .dark .btbl tbody tr:hover { background: rgba(103, 58, 183, 0.08); }
        .btbl td { padding: 14px 16px; font-size: 13.5px; color: var(--btxt); vertical-align: middle; }

        /* ── Buttons ── */
        .bbtn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 18px; border-radius: 12px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            border: none; transition: all 0.2s;
            text-decoration: none; font-family: inherit;
            white-space: nowrap;
        }
        .bbtn-primary { background: var(--bs); color: #fff; box-shadow: 0 4px 12px rgba(103, 58, 183, 0.25); }
        .bbtn-primary:hover { background: var(--bs-dark); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(103, 58, 183, 0.35); }
        
        .bbtn-secondary { background: var(--bsur); color: var(--btxt); border: 1px solid var(--bbor)!important; }
        .bbtn-secondary:hover { background: var(--bs-light); color: var(--bs-dark); border-color: var(--bs-light)!important; }
        
        .bbtn-success { background: var(--success); color: #fff; box-shadow: 0 4px 12px rgba(0, 200, 83, 0.2); }
        .bbtn-success:hover { background: #00a142; transform: translateY(-1px); }
        
        .bbtn-danger { background: var(--error); color: #fff; box-shadow: 0 4px 12px rgba(216, 67, 21, 0.2); }
        .bbtn-danger:hover { background: #b73214; transform: translateY(-1px); }
        .bbtn-sm { padding: 6px 12px!important; font-size: 12px!important; border-radius: 8px; }

        /* ── Badges ── */
        .bbadge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.3px; }
        .bbadge-open { background: var(--warning-light); color: #b78103; }
        .bbadge-progress { background: var(--bp-light); color: var(--bp-dark); }
        .bbadge-pending { background: var(--bs-light); color: var(--bs-dark); }
        .bbadge-closed { background: var(--success-light); color: #007d32; }
        .dark .bbadge-open { background: rgba(255, 193, 7, 0.15); color: #ffe082; }
        .dark .bbadge-closed { background: rgba(0, 200, 83, 0.15); color: #a5d6a7; }

        /* ── Inputs ── */
        .binput { width: 100%; padding: 11px 16px; border: 1.5px solid var(--bbor); border-radius: 12px; font-size: 13.5px; color: var(--btxt); background: var(--bcard); transition: border-color .2s, box-shadow .2s; outline: none; font-family: inherit; }
        .binput:focus { border-color: var(--bs); box-shadow: 0 0 0 3px rgba(103, 58, 183, 0.15); }
        .dark .binput { background: rgba(255, 255, 255, 0.03); }
        .blabel { display: block; font-size: 12px; font-weight: 600; color: var(--btxt2); margin-bottom: 6px; }
        .berr-msg { font-size: 11.5px; color: var(--error); margin-top: 4px; font-weight: 500; }

        /* ── Alerts ── */
        .balert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: 12px; font-size: 13px; margin-bottom: 16px; border: 1px solid transparent; }
        .balert-success { background: var(--success-light); border-color: rgba(0, 200, 83, 0.2); color: #007d32; }
        .balert-error { background: var(--error-light); border-color: rgba(216, 67, 21, 0.2); color: #b73214; }
        .balert-warn { background: var(--warning-light); border-color: rgba(255, 193, 7, 0.2); color: #b78103; }

        /* ── Page Header ── */
        .bph { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .bph-title { font-size: 22px; font-weight: 700; color: var(--btxt); letter-spacing: -0.3px; margin: 0; }
        .bph-sub { font-size: 13px; color: var(--btxt2); margin-top: 4px; font-weight: 500; }

        /* ── Animations ── */
        @keyframes slideInLeft { from { transform: translateX(-20px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeUp { from { transform: translateY(12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .fu { animation: fadeUp 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu1 { animation: fadeUp 0.4s 0.05s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu2 { animation: fadeUp 0.4s 0.1s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu3 { animation: fadeUp 0.4s 0.15s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu4 { animation: fadeUp 0.4s 0.2s cubic-bezier(0.25, 0.8, 0.25, 1) both; }

        /* ── Misc ── */
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .fil { font-variation-settings: 'FILL' 1; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: window.innerWidth > 960, mobileOpen: false }" :class="sidebarOpen ? 'sidebar-open' : 'sidebar-closed'">

    {{-- ═══ TOP HEADER ═══ --}}
    <header class="qtop">
        <div class="logo-area">
            {{-- Logo Section --}}
            <div class="logo-box">
                <span class="material-symbols-outlined">verified_user</span>
            </div>
            <div class="logo-text">
                <h1>SIVERA QA</h1>
                <p>Internal System</p>
            </div>
        </div>
        
        <div style="display:flex;align-items:center;flex:1;">
            {{-- Sidebar Toggle Button --}}
            <button @click="if(window.innerWidth > 960) { sidebarOpen = !sidebarOpen } else { mobileOpen = !mobileOpen }" class="qtop-toggle" title="Toggle Sidebar">
                <span class="material-symbols-outlined">menu</span>
            </button>

            {{-- Search Bar (Berry Styled) --}}
            <div class="qtop-search">
                <span class="material-symbols-outlined">search</span>
                <input type="text" placeholder="Search..." disabled style="cursor:not-allowed;" />
                <span class="material-symbols-outlined" style="cursor:not-allowed;">tune</span>
            </div>
        </div>

        <div class="qtop-act">
            {{-- Theme Toggle --}}
            <button onclick="toggleTheme()" class="qtop-icon-btn" title="Toggle Theme">
                <span class="material-symbols-outlined" style="font-size:18px;">dark_mode</span>
            </button>

            {{-- User Profile Pill (Berry Styled) --}}
            @auth
            <div class="qtop-profile-pill" title="Profile Settings">
                <div class="qs-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="name">{{ auth()->user()->name }}</span>
                <span class="material-symbols-outlined" style="font-size:18px;">settings</span>
            </div>
            @endauth
        </div>
    </header>

    {{-- ═══ SIDEBAR DRAWER ═══ --}}
    <aside class="qs" :style="window.innerWidth <= 960 ? (mobileOpen ? 'transform:translateX(0)' : 'transform:translateX(-260px)') : ''">
        {{-- Sidebar Logo Area --}}
        <div class="qs-header">
            <div class="logo-area">
                <div class="logo-box">
                    <span class="material-symbols-outlined">verified_user</span>
                </div>
                <div class="logo-text">
                    <h1>SIVERA</h1>
                    <p>Version 1.0.0</p>
                </div>
            </div>
        </div>

        {{-- Sidebar Menu Content --}}
        <div class="qs-content">
            <span class="qs-group-label">Dashboard</span>
            <a class="qs-item {{ request()->routeIs('qa.dashboard') ? 'active' : '' }}" href="{{ route('qa.dashboard') }}" wire:navigate>
                <span class="material-symbols-outlined ic {{ request()->routeIs('qa.dashboard') ? 'fil' : '' }}">bar_chart</span>
                <span>Grafik Temuan</span>
            </a>
            <a class="qs-item {{ request()->routeIs('qa.daftar-temuan') ? 'active' : '' }}" href="{{ route('qa.daftar-temuan') }}" wire:navigate>
                <span class="material-symbols-outlined ic {{ request()->routeIs('qa.daftar-temuan') ? 'fil' : '' }}">list_alt</span>
                <span>Daftar Temuan</span>
            </a>
            <a class="qs-item {{ request()->routeIs('qa.rekap') ? 'active' : '' }}" href="{{ route('qa.rekap') }}" wire:navigate>
                <span class="material-symbols-outlined ic {{ request()->routeIs('qa.rekap') ? 'fil' : '' }}">calendar_month</span>
                <span>Rekap Periode</span>
            </a>

            <span class="qs-group-label" style="margin-top:16px;">Master Data</span>
            <a class="qs-item {{ request()->routeIs('qa.master.karyawan') ? 'active' : '' }}" href="{{ route('qa.master.karyawan') }}" wire:navigate>
                <span class="material-symbols-outlined ic">group</span>
                <span>Karyawan</span>
            </a>
            <a class="qs-item {{ request()->routeIs('qa.master.departemen') ? 'active' : '' }}" href="{{ route('qa.master.departemen') }}" wire:navigate>
                <span class="material-symbols-outlined ic">domain</span>
                <span>Departemen</span>
            </a>
            <a class="qs-item {{ request()->routeIs('qa.master.klausul') ? 'active' : '' }}" href="{{ route('qa.master.klausul') }}" wire:navigate>
                <span class="material-symbols-outlined ic">rule</span>
                <span>Klausul PRP</span>
            </a>
            <a class="qs-item {{ request()->routeIs('qa.master.akun') ? 'active' : '' }}" href="{{ route('qa.master.akun') }}" wire:navigate>
                <span class="material-symbols-outlined ic">manage_accounts</span>
                <span>Akun User</span>
            </a>
        </div>

        {{-- Sidebar Footer with logout and user detail --}}
        <div class="qs-footer">
            @auth
            <div class="qs-user">
                <div class="qs-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="overflow:hidden;flex:1;">
                    <div class="qs-uname truncate" style="color:var(--btxt);font-size:13px;font-weight:600;">{{ auth()->user()->name }}</div>
                    <div class="qs-urole" style="color:var(--btxt2);font-size:11px;">QA Admin</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                @csrf
                <button type="submit" class="qs-action">
                    <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                    <span>Sign Out</span>
                </button>
            </form>
            @endauth
        </div>
    </aside>

    {{-- Close sidebar backdrop on mobile --}}
    <div x-show="mobileOpen" @click="mobileOpen = false" class="lg:hidden fixed inset-0 bg-black/40 z-40 transition-opacity"></div>

    {{-- ═══ MAIN LAYOUT WRAPPER ═══ --}}
    <div class="qmain-wrapper">
        <main class="qcontent-container">
            {{ $slot }}
        </main>
    </div>

    {{-- ═══ Berry Custom Confirm Dialog ═══ --}}
    <dialog id="custom-confirm-modal"
        style="position:fixed;inset:0;margin:auto;border-radius:16px;border:1px solid var(--bbor);background:var(--bcard);padding:24px;max-width:380px;width:90%;color:var(--btxt);outline:none;box-shadow:0 24px 60px rgba(0,0,0,.15);"
        class="backdrop:bg-black/40 backdrop:backdrop-blur-sm">
        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--bs-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span class="material-symbols-outlined" style="color:var(--bs-dark);font-size:22px;">help</span>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;margin:0 0 6px;color:var(--btxt);">Konfirmasi Aksi</h3>
                <p id="custom-confirm-message" style="font-size:13px;color:var(--btxt2);margin:0;line-height:1.5;">Apakah Anda yakin?</p>
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button id="custom-confirm-cancel" class="bbtn bbtn-secondary">Batal</button>
            <button id="custom-confirm-ok" class="bbtn bbtn-primary">Lanjutkan</button>
        </div>
    </dialog>

    <script>
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark'); localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark'); localStorage.theme = 'dark';
            }
        }
        document.addEventListener('livewire:navigated', () => {
            if (localStorage.theme === 'dark') document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        });
        (function () {
            let pendingTarget = null, bypassing = false;
            window.confirm = function (message) {
                if (bypassing) return true;
                const dialog = document.getElementById('custom-confirm-modal');
                const msgEl  = document.getElementById('custom-confirm-message');
                if (dialog && msgEl) { msgEl.textContent = message || 'Apakah Anda yakin?'; dialog.showModal(); }
                return false;
            };
            document.addEventListener('click', function (e) {
                if (bypassing) return;
                const t = e.target.closest('[wire\\:confirm]');
                if (t) pendingTarget = t;
            }, true);
            function attachButtons() {
                const dialog = document.getElementById('custom-confirm-modal');
                const btnCancel = document.getElementById('custom-confirm-cancel');
                const btnOk = document.getElementById('custom-confirm-ok');
                if (!dialog || !btnCancel || !btnOk) return;
                btnCancel.onclick = function () { dialog.close(); pendingTarget = null; };
                btnOk.onclick = function () {
                    dialog.close();
                    if (!pendingTarget) return;
                    var el = pendingTarget; pendingTarget = null;
                    bypassing = true; el.click();
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
