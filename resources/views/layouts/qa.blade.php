<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        document.documentElement.classList.remove('dark');
        if (localStorage.getItem('theme') === 'dark') {
            localStorage.removeItem('theme');
        }
        document.addEventListener('livewire:navigated', () => {
            document.documentElement.classList.remove('dark');
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
            top: 0; left: 260px; right: 0;
            z-index: 40;
            box-shadow: 0 1px 10px rgba(0,0,0,.03);
            transition: left 0.3s ease;
        }

        /* Mobile Menu Button */
        .qtop-menu-btn {
            display: none;
            background: var(--bs-light);
            color: var(--bs-dark);
            border: none;
            width: 38px; height: 38px;
            border-radius: 10px;
            align-items: center; justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .qtop-menu-btn:hover {
            background: var(--bs);
            color: #fff;
        }

        .qs-close-btn {
            display: none;
            background: var(--bsur);
            color: var(--btxt2);
            border: none;
            width: 32px; height: 32px;
            border-radius: 8px;
            align-items: center; justify-content: center;
            cursor: pointer;
            margin-left: auto;
            flex-shrink: 0;
        }

        .qs-backdrop {
            display: none;
        }

        /* Topbar Actions */
        .qtop-act { display: flex; align-items: center; gap: 12px; }
        
        /* User Profile Display */
        .qtop-profile-pill {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 14px 5px 5px;
            background: var(--bs-light);
            border-radius: 24px;
            border: none; cursor: default;
            color: var(--bs-dark);
            font-family: inherit;
            user-select: none;
        }
        .qtop-profile-pill .qs-av {
            width: 28px; height: 28px;
            background: var(--bs);
            color: #fff;
            font-weight: 700; font-size: 12px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
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
            overflow: hidden;
        }
        .qs-header {
            height: 80px;
            display: flex; align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid var(--bbor);
            flex-shrink: 0;
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
        .logo-text p { font-size: 10px; color: var(--btxt2); margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        .qs-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px;
            scrollbar-width: thin;
            scrollbar-color: rgba(0,0,0,0.1) transparent;
        }
        .qs-content::-webkit-scrollbar { width: 3px; }
        .qs-content::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 4px; }
        
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
        .qs-av {
            width: 32px; height: 32px;
            background: var(--bs); color: #fff;
            font-weight: 700; font-size: 13px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
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
            margin-left: 260px;
        }
        .qcontent-container {
            padding: 24px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .qtop-logo { display: none; }

        /* Mobile & Tablet Responsiveness */
        @media (max-width: 960px) {
            .qtop { left: 0 !important; right: 0 !important; padding: 0 16px; height: 64px; }
            .qmain-wrapper { margin-left: 0 !important; padding-top: 64px; }
            .qcontent-container { padding: 16px; }
            .qtop-logo { display: flex !important; align-items: center; gap: 10px; }
            .qtop-menu-btn { display: flex !important; }
            .qs-close-btn { display: flex !important; }

            .qs {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                z-index: 100 !important;
                box-shadow: 0 0 30px rgba(0,0,0,0.25);
                display: flex !important;
            }
            .qs.qs-open {
                transform: translateX(0) !important;
            }
            .qs-backdrop {
                display: block;
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.45);
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
                z-index: 90;
                opacity: 0; pointer-events: none;
                transition: opacity 0.3s ease;
            }
            .qs-backdrop.qs-open {
                opacity: 1; pointer-events: auto;
            }
        }

        @media (max-width: 640px) {
            .qtop { padding: 0 12px; height: 58px; }
            .qtop-act { gap: 8px; }
            .qtop-profile-pill { padding: 4px; }
            .qtop-profile-pill .name { display: none; }
            .qtop-logo .logo-box { width: 32px; height: 32px; border-radius: 8px; }
            .qtop-logo .logo-box span { font-size: 16px; }
            .qtop-logo .logo-text h1 { font-size: 14px; }
            .qtop-logo .logo-text p { display: none; }
            
            .qmain-wrapper { padding-top: 58px; }
            .qcontent-container { padding: 12px; }
            
            .bcard { padding: 14px !important; border-radius: 10px; }
            .bph { margin-bottom: 14px; gap: 8px; flex-direction: column; align-items: flex-start; }
            .bph-title { font-size: 17px; }
            .bph-sub { font-size: 12px; }
            
            .bstat { padding: 12px; gap: 10px; border-radius: 10px; }
            .bstat-icon { width: 38px; height: 38px; border-radius: 8px; }
            .bstat-val { font-size: 18px; }
            .bstat-lbl { font-size: 11px; }

            .btbl th, .btbl td { padding: 10px 12px; font-size: 12px; }
            
            .bbtn { padding: 8px 14px; font-size: 12px; border-radius: 8px; width: 100%; justify-content: center; }
            .bbtn-sm { padding: 5px 10px !important; font-size: 11.5px !important; width: auto; }

            .binput { padding: 9px 12px; font-size: 12.5px; border-radius: 8px; }
            .blabel { font-size: 11.5px; }

            div[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }
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
        .btbl tbody tr { border-bottom: 1px solid var(--bbor); transition: background .15s; }
        .btbl tbody tr:last-child { border-bottom: none; }
        .btbl tbody tr:hover { background: rgba(103, 58, 183, 0.03); }
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

        /* ── Inputs ── */
        .binput { width: 100%; padding: 11px 16px; border: 1.5px solid var(--bbor); border-radius: 12px; font-size: 13.5px; color: var(--btxt); background: var(--bcard); transition: border-color .2s, box-shadow .2s; outline: none; font-family: inherit; }
        .binput:focus { border-color: var(--bs); box-shadow: 0 0 0 3px rgba(103, 58, 183, 0.15); }
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
        @keyframes fadeUp { from { transform: translateY(12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .fu { animation: fadeUp 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu1 { animation: fadeUp 0.4s 0.05s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu2 { animation: fadeUp 0.4s 0.1s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu3 { animation: fadeUp 0.4s 0.15s cubic-bezier(0.25, 0.8, 0.25, 1) both; }

        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .fil { font-variation-settings: 'FILL' 1; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
    @livewireStyles
</head>
<body>

    {{-- ═══ MOBILE BACKDROP OVERLAY ═══ --}}
    <div id="mobile-drawer-backdrop" class="qs-backdrop"></div>

    {{-- ═══ TOP HEADER ═══ --}}
    <header class="qtop">
        <div style="display:flex; align-items:center; gap:10px;">
            {{-- Mobile Drawer Menu Button --}}
            <button id="mobile-menu-btn" class="qtop-menu-btn" aria-label="Toggle Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <div class="logo-area qtop-logo">
                <div class="logo-box">
                    <span class="material-symbols-outlined">verified_user</span>
                </div>
                <div class="logo-text">
                    <h1>SIVERA</h1>
                    <p>Internal System</p>
                </div>
            </div>
        </div>

        <div style="flex:1;"></div>

        <div class="qtop-act">
            {{-- Static User Profile Display --}}
            @auth
            <div class="qtop-profile-pill">
                <div class="qs-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="name">{{ auth()->user()->name }}</span>
            </div>
            @endauth
        </div>
    </header>

    {{-- ═══ SIDEBAR DRAWER ═══ --}}
    <aside id="sidebar-drawer" class="qs">
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
            <button id="mobile-menu-close" class="qs-close-btn" aria-label="Close Menu">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Sidebar Menu Content --}}
        <div class="qs-content">
            @if(auth()->user()->isSuperAdmin())
                <span class="qs-group-label">Super Admin Panel</span>
                <a class="qs-item {{ request()->routeIs('qa.master.akun') ? 'active' : '' }}" href="{{ route('qa.master.akun') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('qa.master.akun') ? 'fil' : '' }}">manage_accounts</span>
                    <span>Manajemen Akun User</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.karyawan') ? 'active' : '' }}" href="{{ route('qa.master.karyawan') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('qa.master.karyawan') ? 'fil' : '' }}">badge</span>
                    <span>Master PIC</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.departemen') ? 'active' : '' }}" href="{{ route('qa.master.departemen') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('qa.master.departemen') ? 'fil' : '' }}">domain</span>
                    <span>Master Departemen</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.klausul') ? 'active' : '' }}" href="{{ route('qa.master.klausul') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('qa.master.klausul') ? 'fil' : '' }}">rule</span>
                    <span>Klausul PRP</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.akun') ? 'active' : '' }}" href="{{ route('qa.master.akun') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('qa.master.akun') ? 'fil' : '' }}">manage_accounts</span>
                    <span>Manajemen Akun User</span>
                </a>

                <span class="qs-group-label" style="margin-top:16px;">Master Data BOS'Q</span>
                <a class="qs-item {{ request()->routeIs('bosq.qa.master.line') ? 'active' : '' }}" href="{{ route('bosq.qa.master.line') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.master.line') ? 'fil' : '' }}">precision_manufacturing</span>
                    <span>Master Line</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.master.subarea') ? 'active' : '' }}" href="{{ route('bosq.qa.master.subarea') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.master.subarea') ? 'fil' : '' }}">location_on</span>
                    <span>Master Sub Area</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.master.elemen') ? 'active' : '' }}" href="{{ route('bosq.qa.master.elemen') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.master.elemen') ? 'fil' : '' }}">category</span>
                    <span>Master Elemen QFS</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.master.karyawan') ? 'active' : '' }}" href="{{ route('bosq.qa.master.karyawan') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.master.karyawan') ? 'fil' : '' }}">groups</span>
                    <span>Divisi Manajemen</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.dashboard') ? 'active' : '' }}" href="{{ route('bosq.qa.dashboard') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.dashboard') ? 'fil' : '' }}">dashboard</span>
                    <span>Grafik Temuan BOS'Q</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.daftar-observasi') ? 'active' : '' }}" href="{{ route('bosq.qa.daftar-observasi') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.daftar-observasi') ? 'fil' : '' }}">list_alt</span>
                    <span>Daftar Observasi BOS'Q</span>
                </a>

                <span class="qs-group-label" style="margin-top:16px;">Monitoring & Analytics</span>
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
                <a class="qs-item" href="{{ route('bosq.qa.dashboard') }}" style="margin-top:6px;background:var(--bsur);border:1px solid var(--bbor);font-weight:600;color:#1565c0;">
                    <span class="material-symbols-outlined ic" style="color:#1565c0;">swap_horiz</span>
                    <span>Beralih ke BOS'Q</span>
                </a>
            @else
                <span class="qs-group-label">Dashboard QA</span>
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

                <span class="qs-group-label" style="margin-top:16px;">Mode Pelapor</span>
                <a class="qs-item {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('beranda') ? 'fil' : '' }}">add_a_photo</span>
                    <span>Lapor Temuan Saya</span>
                </a>

                <span class="qs-group-label" style="margin-top:16px;">Master Data</span>
                <a class="qs-item {{ request()->routeIs('qa.master.akun') ? 'active' : '' }}" href="{{ route('qa.master.akun') }}" wire:navigate>
                    <span class="material-symbols-outlined ic {{ request()->routeIs('qa.master.akun') ? 'fil' : '' }}">manage_accounts</span>
                    <span>Manajemen Akun User</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.karyawan') ? 'active' : '' }}" href="{{ route('qa.master.karyawan') }}" wire:navigate>
                    <span class="material-symbols-outlined ic">badge</span>
                    <span>Master PIC</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.departemen') ? 'active' : '' }}" href="{{ route('qa.master.departemen') }}" wire:navigate>
                    <span class="material-symbols-outlined ic">domain</span>
                    <span>Master Departemen</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.klausul') ? 'active' : '' }}" href="{{ route('qa.master.klausul') }}" wire:navigate>
                    <span class="material-symbols-outlined ic">rule</span>
                    <span>Klausul PRP</span>
                </a>

                <span class="qs-group-label" style="margin-top:16px;">Sistem & Dual Portal</span>
                <a class="qs-item" href="{{ route('bosq.qa.dashboard') }}" style="background:var(--bsur);border:1px solid var(--bbor);font-weight:600;color:#1565c0;">
                    <span class="material-symbols-outlined ic" style="color:#1565c0;">swap_horiz</span>
                    <span>Beralih ke BOS'Q</span>
                </a>
            @endif
        </div>

        {{-- Sidebar Footer with logout and user detail --}}
        <div class="qs-footer">
            @auth
            <div class="qs-user">
                <div class="qs-av" style="{{ auth()->user()->isSuperAdmin() ? 'background:#7c3aed;' : '' }}">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="overflow:hidden;flex:1;">
                    <div class="qs-uname truncate" style="color:var(--btxt);font-size:13px;font-weight:600;">{{ auth()->user()->name }}</div>
                    <div class="qs-urole" style="color:var(--btxt2);font-size:11px;">{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'QA Admin' }}</div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="qs-action" style="margin-bottom: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size:18px;">manage_accounts</span>
                <span>Pengaturan Profil</span>
            </a>
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
        (function () {
            document.documentElement.classList.remove('dark');
            if (localStorage.getItem('theme') === 'dark') {
                localStorage.removeItem('theme');
            }
            document.addEventListener('livewire:navigated', () => {
                document.documentElement.classList.remove('dark');
            });

            function setupMobileMenu() {
                const btnOpen = document.getElementById('mobile-menu-btn');
                const btnClose = document.getElementById('mobile-menu-close');
                const sidebar = document.getElementById('sidebar-drawer');
                const backdrop = document.getElementById('mobile-drawer-backdrop');

                if (!btnOpen || !sidebar || !backdrop) return;

                function openDrawer() {
                    sidebar.classList.add('qs-open');
                    backdrop.classList.add('qs-open');
                    document.body.style.overflow = 'hidden';
                }

                function closeDrawer() {
                    sidebar.classList.remove('qs-open');
                    backdrop.classList.remove('qs-open');
                    document.body.style.overflow = '';
                }

                btnOpen.onclick = openDrawer;
                if (btnClose) btnClose.onclick = closeDrawer;
                backdrop.onclick = closeDrawer;

                const navItems = sidebar.querySelectorAll('.qs-item');
                navItems.forEach(item => {
                    item.onclick = closeDrawer;
                });
            }

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
            document.addEventListener('DOMContentLoaded', () => {
                attachButtons();
                setupMobileMenu();
            });
            document.addEventListener('livewire:navigated', () => {
                attachButtons();
                setupMobileMenu();
            });
        })();
    </script>
    @livewireScripts
</body>
</html>
