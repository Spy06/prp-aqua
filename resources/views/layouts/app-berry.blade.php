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

        .qtop {
            background: var(--bcard);
            border-bottom: 1px solid var(--bbor);
            height: 68px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 40;
            box-shadow: 0 1px 12px rgba(0,0,0,0.05);
            transition: left 0.3s ease;
        }

        .qtop-toggle {
            width: 34px; height: 34px; border-radius: 8px;
            background: var(--bp-light); color: var(--bp-dark);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; border: none; outline: none;
        }
        .qtop-toggle:hover { background: var(--bp-dark); color: #fff; }

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

        .qs {
            width: 250px; background: var(--bside);
            height: 100vh; position: fixed; left: 0; top: 0;
            display: flex; flex-direction: column;
            z-index: 50; border-right: 1px solid var(--bbor);
            transition: transform 0.3s ease; overflow: hidden;
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

        .qmain { min-height: 100vh; padding-top: 68px; transition: margin-left 0.3s ease; }
        .qcontent { padding: 24px; max-width: 1400px; margin: 0 auto; width: 100%; }

        .sidebar-open .qs { transform: translateX(0); }
        .sidebar-open .qtop { left: 250px; }
        .sidebar-open .qmain { margin-left: 250px; }
        .sidebar-closed .qs { transform: translateX(-250px); }
        .sidebar-closed .qtop { left: 0; }
        .sidebar-closed .qmain { margin-left: 0; }

        @media (max-width: 960px) {
            .qtop, .sidebar-open .qtop, .sidebar-closed .qtop {
                left: 0 !important; right: 0 !important; padding: 0 16px; height: 60px;
            }
            .qmain, .sidebar-open .qmain, .sidebar-closed .qmain {
                margin-left: 0 !important; padding-top: 60px;
            }
            .qcontent { padding: 16px; }
            .sidebar-open .qs { transform: translateX(0); }
            .sidebar-closed .qs { transform: translateX(-250px); }
        }
        @media (max-width: 640px) {
            .qtop, .sidebar-open .qtop, .sidebar-closed .qtop { padding: 0 12px; height: 56px; }
            .qmain { padding-top: 56px; }
            .qcontent { padding: 12px; }
            .qtop-profile-pill .name { display: none; }
        }

        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .fil { font-variation-settings: 'FILL' 1; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: window.innerWidth > 960, mobileOpen: false }" :class="sidebarOpen ? 'sidebar-open' : 'sidebar-closed'">

    <header class="qtop">
        <div style="display:flex;align-items:center;flex:1;">
            <button @click="if(window.innerWidth > 960) { sidebarOpen = !sidebarOpen } else { mobileOpen = !mobileOpen }" class="qtop-toggle" title="Toggle Sidebar">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>

        <div class="qtop-act">
            @auth
            <div class="qtop-profile-pill">
                <div class="qtop-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="name">{{ auth()->user()->name }}</span>
            </div>
            @endauth
        </div>
    </header>

    <aside class="qs" :style="window.innerWidth <= 960 ? (mobileOpen ? 'transform:translateX(0)' : 'transform:translateX(-250px)') : ''">
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

    <div x-show="mobileOpen" @click="mobileOpen = false"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:45;"
         x-transition.opacity></div>

    <div class="qmain">
        <main class="qcontent">
            {{ $slot }}
        </main>
    </div>

    <script>
        (function () {
            document.documentElement.classList.remove('dark');
            if (localStorage.getItem('theme') === 'dark') {
                localStorage.removeItem('theme');
            }
            document.addEventListener('livewire:navigated', () => {
                document.documentElement.classList.remove('dark');
            });
        })();
    </script>
    @livewireScripts
</body>
</html>
