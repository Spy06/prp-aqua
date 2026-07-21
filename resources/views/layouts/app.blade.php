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
    <title>{{ $title ?? 'SIVERA — Verifikasi PRP' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
    <style>
        /* ── Berry Design System Variables ── */
        :root {
            --bp: #1976d2;
            --bp-light: #e3f2fd;
            --bp-dark: #1565c0;
            --bs: #7c4dff;
            --bs-light: #ede7f6;
            --bs-dark: #651fff;
            --bsur: #f0f4f8;
            --bcard: #ffffff;
            --bside: #ffffff;
            --bbor: #dde3ec;
            --btxt: #1a1f36;
            --btxt2: #5a6478;
            --success: #2e7d32;
            --success-light: #e8f5e9;
            --error: #c62828;
            --error-light: #ffebee;
            --warning: #e65100;
            --warning-light: #fff3e0;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bsur);
            color: var(--btxt);
            margin: 0; min-height: 100vh; overflow-x: hidden;
        }

        /* ── Top Header ── */
        .qtop {
            background: var(--bcard);
            border-bottom: 1px solid var(--bbor);
            height: 68px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            position: fixed; top: 0; left: 250px; right: 0;
            z-index: 40;
            box-shadow: 0 1px 12px rgba(0,0,0,0.05);
            transition: left 0.3s ease;
        }

        .qtop-act { display: flex; align-items: center; gap: 10px; }

        .qtop-profile-pill {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 14px 5px 5px;
            background: var(--bp-light); border-radius: 24px;
            border: 1px solid rgba(25,118,210,0.15);
            cursor: default; color: var(--bp-dark);
            font-family: inherit; user-select: none;
        }
        .qtop-av {
            width: 26px; height: 26px; background: var(--bp); color: #fff;
            font-weight: 700; font-size: 11px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .qtop-profile-pill .name { font-size: 13px; font-weight: 600; }

        /* ── Sidebar ── */
        .qs {
            width: 250px; background: var(--bside);
            height: 100vh; position: fixed; left: 0; top: 0;
            display: flex; flex-direction: column;
            z-index: 50; border-right: 1px solid var(--bbor);
            overflow: hidden;
        }
        .qs-header {
            height: 68px; display: flex; align-items: center;
            padding: 0 20px; border-bottom: 1px solid var(--bbor); flex-shrink: 0;
        }
        .logo-area { display: flex; align-items: center; gap: 12px; width: 220px; flex-shrink: 0; }
        .logo-box {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #1976d2, #42a5f5);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(25,118,210,0.3);
        }
        .logo-box span { color: #fff; font-size: 18px; }
        .logo-text h1 { font-size: 15px; font-weight: 700; color: var(--bp); letter-spacing: -0.2px; margin: 0; }
        .logo-text p { font-size: 9.5px; color: var(--btxt2); margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; }

        .qs-content { flex: 1; overflow-y: auto; padding: 16px 12px; }
        .qs-content::-webkit-scrollbar { width: 3px; }
        .qs-content::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 4px; }

        .qs-section-label {
            font-size: 10.5px; font-weight: 700; color: var(--btxt2);
            text-transform: uppercase; letter-spacing: 1px;
            padding: 10px 10px 5px; display: block;
        }
        .qs-item {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 14px; border-radius: 10px; cursor: pointer;
            transition: all 0.2s; color: var(--btxt);
            font-size: 13.5px; font-weight: 500; text-decoration: none; margin-bottom: 2px;
        }
        .qs-item:hover { background: var(--bp-light); color: var(--bp-dark); }
        .qs-item.active {
            background: linear-gradient(135deg, var(--bp-light), rgba(25,118,210,0.12));
            color: var(--bp-dark); font-weight: 600;
            box-shadow: 0 2px 8px rgba(25,118,210,0.1);
        }
        .qs-item .ic { font-size: 19px; width: 20px; text-align: center; }

        .qs-footer {
            padding: 14px; border-top: 1px solid var(--bbor);
            background: var(--bsur); flex-shrink: 0;
        }
        .qs-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; background: var(--bcard); border-radius: 10px;
            border: 1px solid var(--bbor); margin-bottom: 8px;
        }
        .qs-av {
            width: 32px; height: 32px; background: var(--bp); color: #fff;
            font-weight: 700; font-size: 13px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .qs-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px; color: var(--btxt2);
            font-size: 13px; font-weight: 600; transition: all 0.2s;
            cursor: pointer; width: 100%; background: none; border: none;
            text-align: left; font-family: inherit;
        }
        .qs-logout:hover { background: var(--error-light); color: var(--error); }

        /* ── Layout Wrapper ── */
        .qmain { min-height: 100vh; padding-top: 68px; margin-left: 250px; }
        .qcontent { padding: 24px; max-width: 1400px; margin: 0 auto; width: 100%; }

        .qtop-logo { display: none; }

        /* ── Responsive ── */
        @media (max-width: 960px) {
            .qtop { left: 0 !important; right: 0 !important; padding: 0 16px; height: 60px; }
            .qmain { margin-left: 0 !important; padding-top: 60px; }
            .qcontent { padding: 16px; }
            .qs { display: none; }
            .mobile-logout { display: block !important; }
            .qtop-logo { display: flex !important; align-items: center; gap: 10px; }
        }
        @media (max-width: 640px) {
            .qtop { padding: 0 12px; height: 56px; }
            .qmain { padding-top: 56px; }
            .qcontent { padding: 12px; }
            .qtop-profile-pill .name { display: none; }
            .qtop-logo .logo-box { width: 32px; height: 32px; border-radius: 8px; }
            .qtop-logo .logo-box span { font-size: 16px; }
            .qtop-logo .logo-text h1 { font-size: 14px; }
            .qtop-logo .logo-text p { display: none; }
        }

        /* ── Berry Cards ── */
        .bcard {
            background: var(--bcard); border: 1px solid var(--bbor);
            border-radius: 14px; overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
        }
        .bcard-header {
            padding: 16px 20px; border-bottom: 1px solid var(--bbor);
            display: flex; align-items: center; gap: 12px;
            background: var(--bsur);
        }
        .bcard-hicon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .bcard-body { padding: 20px; }
        @media (max-width: 640px) { .bcard-body { padding: 14px; } }

        /* ── Stat Cards ── */
        .bstat {
            background: var(--bcard); border: 1px solid var(--bbor);
            border-radius: 12px; padding: 18px 16px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: all 0.25s;
        }
        .bstat:hover { box-shadow: 0 6px 20px rgba(25,118,210,0.1); transform: translateY(-2px); }
        .bstat-icon {
            width: 46px; height: 46px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .bstat-val { font-size: 26px; font-weight: 700; color: var(--btxt); line-height: 1; }
        .bstat-lbl { font-size: 11.5px; color: var(--btxt2); margin-top: 3px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ── Page Header ── */
        .bph { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 10px; }
        .bph-title { font-size: 20px; font-weight: 700; color: var(--btxt); letter-spacing: -0.3px; margin: 0; }
        .bph-sub { font-size: 13px; color: var(--btxt2); margin-top: 3px; }
        @media (max-width: 640px) { .bph { flex-direction: column; align-items: flex-start; } .bph-title { font-size: 18px; } }

        /* ── Status Badges ── */
        .sbadge {
            display: inline-flex; align-items: center;
            padding: 4px 11px; border-radius: 20px; font-size: 11px;
            font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase;
            border: 1px solid transparent; white-space: nowrap;
        }
        .sbadge-open { background: #fff8e1; color: #e65100; border-color: #ffe082; }
        .sbadge-progress { background: #e3f2fd; color: #1565c0; border-color: #90caf9; }
        .sbadge-pending { background: #fce4ec; color: #c62828; border-color: #f48fb1; }
        .sbadge-closed { background: #e8f5e9; color: #2e7d32; border-color: #a5d6a7; }

        /* ── Buttons ── */
        .bbtn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 18px; border-radius: 10px; font-size: 13px;
            font-weight: 600; cursor: pointer; border: none;
            transition: all 0.2s; text-decoration: none; font-family: inherit;
            white-space: nowrap;
        }
        .bbtn-primary { background: var(--bp); color: #fff; box-shadow: 0 4px 12px rgba(25,118,210,0.25); }
        .bbtn-primary:hover { background: var(--bp-dark); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(25,118,210,0.35); }
        .bbtn-secondary {
            background: var(--bsur); color: var(--btxt);
            border: 1.5px solid var(--bbor) !important;
        }
        .bbtn-secondary:hover { background: var(--bp-light); color: var(--bp-dark); }
        .bbtn-success { background: #2e7d32; color: #fff; }
        .bbtn-success:hover { background: #1b5e20; transform: translateY(-1px); }
        .bbtn-danger { background: #c62828; color: #fff; }
        .bbtn-danger:hover { background: #b71c1c; transform: translateY(-1px); }
        .bbtn-purple { background: var(--bs); color: #fff; box-shadow: 0 4px 12px rgba(124,77,255,0.25); }
        .bbtn-purple:hover { background: var(--bs-dark); transform: translateY(-1px); }
        .bbtn-sm { padding: 6px 12px !important; font-size: 12px !important; border-radius: 8px; }

        /* ── Form Inputs ── */
        .binput {
            width: 100%; padding: 11px 14px; border: 1.5px solid var(--bbor);
            border-radius: 10px; font-size: 13.5px; color: var(--btxt);
            background: var(--bcard); transition: border-color .2s, box-shadow .2s;
            outline: none; font-family: inherit;
        }
        .binput:focus { border-color: var(--bp); box-shadow: 0 0 0 3px rgba(25,118,210,0.12); }
        .binput:disabled { background: var(--bsur); color: var(--btxt2); cursor: not-allowed; opacity: 0.8; }
        .blabel { display: block; font-size: 12px; font-weight: 600; color: var(--btxt2); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .berr { font-size: 11.5px; color: var(--error); margin-top: 4px; font-weight: 500; }

        /* ── Alert Boxes ── */
        .balert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: 13px; border: 1px solid transparent; }
        .balert-success { background: #e8f5e9; color: #2e7d32; border-color: #c8e6c9; }
        .balert-error { background: #ffebee; color: #c62828; border-color: #ffcdd2; }
        .balert-warn { background: #fff3e0; color: #e65100; border-color: #ffe0b2; }
        .balert-info { background: #e3f2fd; color: #1565c0; border-color: #bbdefb; }

        /* ── Info Fields ── */
        .inf-label { font-size: 10.5px; font-weight: 700; color: var(--btxt2); text-transform: uppercase; letter-spacing: 0.9px; margin-bottom: 4px; }
        .inf-value { font-size: 14px; font-weight: 600; color: var(--btxt); }
        .inf-text {
            font-size: 13.5px; color: var(--btxt); line-height: 1.65;
            white-space: pre-wrap; background: var(--bsur);
            padding: 12px 14px; border-radius: 10px; border: 1px solid var(--bbor);
        }

        /* ── Urgency Banners ── */
        .urgency-overdue { background: #ffebee; border-bottom: 1px solid #ffcdd2; color: #c62828; padding: 8px 16px; display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 700; }
        .urgency-soon { background: #fff3e0; border-bottom: 1px solid #ffe0b2; color: #e65100; padding: 8px 16px; display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 700; }
        .urgency-pending { background: #f3e5f5; border-bottom: 1px solid #e1bee7; color: #6a1b9a; padding: 8px 16px; display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 700; }

        /* ── Temuan Cards (list view) ── */
        .tcard {
            background: var(--bcard); border: 1px solid var(--bbor);
            border-radius: 14px; overflow: hidden;
            display: flex; flex-direction: column;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: all 0.25s; text-decoration: none; color: inherit;
        }
        .tcard:hover {
            box-shadow: 0 8px 28px rgba(25,118,210,0.12);
            transform: translateY(-2px); border-color: var(--bp);
        }
        .tcard-body { padding: 16px; flex: 1; }
        .tcard-footer {
            padding: 10px 16px; border-top: 1px solid var(--bbor);
            background: var(--bsur); display: flex;
            justify-content: space-between; align-items: center;
            font-size: 12px; color: var(--btxt2); gap: 8px;
        }
        .tcard-dept { font-size: 15px; font-weight: 700; color: var(--btxt); margin: 0 0 6px; }
        .tcard-sub { font-size: 12.5px; color: var(--bp); font-weight: 600; display: flex; align-items: center; gap: 4px; margin-bottom: 8px; }
        .tcard-desc { font-size: 13px; color: var(--btxt2); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.55; }

        /* ── Tcard Grid ── */
        .tcard-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 1024px) { .tcard-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { .tcard-grid { grid-template-columns: 1fr; gap: 12px; } }

        /* ── Breadcrumb ── */
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--btxt2); margin-bottom: 20px; flex-wrap: wrap; }
        .breadcrumb a { color: var(--btxt2); text-decoration: none; }
        .breadcrumb a:hover { color: var(--bp); }
        .breadcrumb .sep { opacity: 0.5; }

        /* ── Misc ── */
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .fil { font-variation-settings: 'FILL' 1; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        @keyframes fadeUp { from { transform: translateY(12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .fu { animation: fadeUp 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu1 { animation: fadeUp 0.45s 0.05s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu2 { animation: fadeUp 0.45s 0.1s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu3 { animation: fadeUp 0.45s 0.15s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @livewireStyles
</head>
<body>

    {{-- ═══ TOP HEADER ═══ --}}
    <header class="qtop">
        <div class="logo-area qtop-logo">
            <div class="logo-box">
                <span class="material-symbols-outlined">factory</span>
            </div>
            <div class="logo-text">
                <h1>SIVERA</h1>
                <p>Verifikasi PRP</p>
            </div>
        </div>

        <div style="flex:1;"></div>

        <div class="qtop-act">
            @auth
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="qtop-profile-pill">
                    <div class="qtop-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="name">{{ auth()->user()->name }}</span>
                </div>
                <!-- Mobile Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="mobile-logout" style="display:none; margin: 0;">
                    @csrf
                    <button type="submit" class="bbtn bbtn-secondary bbtn-sm" style="padding: 6px 8px; border-color: var(--error) !important; color: var(--error);" title="Sign Out">
                        <span class="material-symbols-outlined" style="font-size: 18px; margin: 0;">logout</span>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </header>

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="qs">
        <div class="qs-header">
            <div class="logo-area">
                <div class="logo-box">
                    <span class="material-symbols-outlined">factory</span>
                </div>
                <div class="logo-text">
                    <h1>SIVERA</h1>
                    <p>Internal System</p>
                </div>
            </div>
        </div>

        <div class="qs-content">
            @auth
                @if(auth()->user()->role === 'pelapor')
                    <span class="qs-section-label">Menu Utama</span>
                    <a class="qs-item {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}" wire:navigate>
                        <span class="material-symbols-outlined ic {{ request()->routeIs('beranda') ? 'fil' : '' }}">home</span>
                        <span>Beranda</span>
                    </a>

                @elseif(auth()->user()->role === 'pic')
                    <span class="qs-section-label">Menu Utama</span>
                    <a class="qs-item {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}" wire:navigate>
                        <span class="material-symbols-outlined ic {{ request()->routeIs('beranda') ? 'fil' : '' }}">assignment</span>
                        <span>Temuan Saya</span>
                    </a>
                @endif
            @endauth
        </div>

        <div class="qs-footer">
            @auth
            <div class="qs-user">
                <div class="qs-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="overflow:hidden;flex:1;">
                    <div class="truncate" style="color:var(--btxt);font-size:13px;font-weight:600;">{{ auth()->user()->name }}</div>
                    <div style="color:var(--btxt2);font-size:11px;text-transform:capitalize;">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                @csrf
                <button type="submit" class="qs-logout">
                    <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                    <span>Sign Out</span>
                </button>
            </form>
            @endauth
        </div>
    </aside>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="qmain">
        <main class="qcontent">
            {{ $slot }}
        </main>
    </div>

    {{-- ═══ Custom Confirm Dialog ═══ --}}
    <dialog id="custom-confirm-modal"
        style="position:fixed;inset:0;margin:auto;border-radius:16px;border:1px solid var(--bbor);background:var(--bcard);padding:24px;max-width:380px;width:90%;color:var(--btxt);outline:none;box-shadow:0 24px 60px rgba(0,0,0,.15);"
        class="backdrop:bg-black/40 backdrop:backdrop-blur-sm">
        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;">
            <div style="width:40px;height:40px;border-radius:10px;background:#e3f2fd;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span class="material-symbols-outlined" style="color:#1565c0;font-size:22px;">help</span>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;margin:0 0 6px;color:var(--btxt);">Konfirmasi Aksi</h3>
                <p id="custom-confirm-message" style="font-size:13px;color:var(--btxt2);margin:0;line-height:1.5;">Apakah Anda yakin?</p>
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button id="custom-confirm-cancel" class="bbtn bbtn-secondary" style="padding:8px 16px;">Batal</button>
            <button id="custom-confirm-ok" class="bbtn bbtn-primary" style="padding:8px 16px;">Lanjutkan</button>
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
                const dialog    = document.getElementById('custom-confirm-modal');
                const btnCancel = document.getElementById('custom-confirm-cancel');
                const btnOk     = document.getElementById('custom-confirm-ok');
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