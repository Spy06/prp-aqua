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
            --bp: #0d47a1;
            --bp-light: #e3f2fd;
            --bp-dark: #0b3c8f;
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
            background: linear-gradient(135deg, #0d47a1, #2196f3);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(13,71,161,0.3);
        }
        .logo-box span { color: #fff; font-size: 18px; }
        .logo-text h1 { font-size: 15px; font-weight: 700; color: var(--bp); letter-spacing: -0.2px; margin: 0; }
        .logo-text p { font-size: 9.5px; color: var(--btxt2); margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; }

        .qmain { min-height: 100vh; padding-top: 68px; margin-left: 250px; }
        .qcontent { padding: 24px; max-width: 1400px; margin: 0 auto; width: 100%; }

        .qtop-logo { display: none; }

        @media (max-width: 960px) {
            .qtop { left: 0 !important; right: 0 !important; padding: 0 16px; height: 60px; }
            .qmain { margin-left: 0 !important; padding-top: 60px; }
            .qcontent { padding: 16px; }
            .qs { display: none; }
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

        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .fil { font-variation-settings: 'FILL' 1; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
    @livewireStyles
</head>
<body>

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
            <div class="qtop-profile-pill">
                <div class="qtop-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="name">{{ auth()->user()->name }}</span>
            </div>
            @endauth
        </div>
    </header>

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
    </aside>

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
