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
    <title>{{ $title ?? 'SIVERA — Sistem Verifikasi PRP' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
    <style>
        :root {
            --bp: #2196f3;
            --bp-light: #e3f2fd;
            --bp-dark: #1e88e5;
            --bs: #673ab7;
            --bs-light: #ede7f6;
            --bs-dark: #5e35b1;
            --bsur: #eef2f6;
            --bcard: #ffffff;
            --bside: #ffffff;
            --bbor: #e3e8ef;
            --btxt: #1a1f2e;
            --btxt2: #697586;
            --bp-light: #e3f2fd;
            --bs-light: #ede7f6;
            --success: #00c853;
            --success-light: #e8f5e9;
            --error: #d84315;
            --error-light: #fbe9e7;
            --warning: #ffc107;
            --warning-light: #fff8e1;
        }
        .dark {
            --bsur: #0f172a;
            --bcard: #1e293b;
            --bside: #0f172a;
            --bbor: #334155;
            --btxt: #f8fafc;
            --btxt2: #94a3b8;
            --bp-light: rgba(59, 130, 246, 0.15);
            --bs-light: rgba(139, 92, 246, 0.15);
            --bp: #60a5fa;
            --bs: #a78bfa;
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
            background: linear-gradient(135deg, #2196f3, #1565c0);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 10px rgba(33, 150, 243, 0.35);
        }
        .logo-box span { color: #fff; font-size: 20px; }
        .logo-text h1 { font-size: 16px; font-weight: 700; color: var(--bp); letter-spacing: -0.3px; margin: 0; }
        .dark .logo-text h1 { color: #93c5fd; }
        .logo-text p { font-size: 10px; color: var(--btxt2); margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        .qtop-toggle {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: var(--bp-light);
            color: var(--bp-dark);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease-in-out;
            border: none; outline: none;
        }
        .qtop-toggle:hover { background: var(--bp-dark); color: #fff; }
        .dark .qtop-toggle { color: #93c5fd; }
        .dark .qtop-toggle:hover { background: var(--bp); color: #fff; }

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
        .qtop-icon-btn:hover { background: var(--bp-dark); color: #fff; }

        .qtop-profile-pill {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 14px 5px 5px;
            background: var(--bp-light);
            border-radius: 24px;
            border: none; cursor: default;
            color: var(--bp-dark);
            font-family: inherit;
            user-select: none;
        }
        .qtop-profile-pill .qs-av {
            width: 28px; height: 28px;
            background: var(--bp);
            color: #fff;
            font-weight: 700; font-size: 12px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .qtop-profile-pill .name { font-size: 13px; font-weight: 600; }

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
        .qs-item:hover { background: var(--bp-light); color: var(--bp-dark); }
        .qs-item.active { background: var(--bp-light); color: var(--bp-dark); font-weight: 600; }
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
            width: 34px; height: 34px;
            background: var(--bp);
            color: #fff; font-weight: 700; font-size: 13px;
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
        .qs-footer .qs-action:hover { background: var(--error-light); color: var(--error); }

        /* ── Main Layout Spacing ── */
        .qmain-wrapper { min-height: 100vh; padding-top: 80px; transition: margin-left 0.3s ease; }
        .qcontent-container { padding: 24px; max-width: 1400px; margin: 0 auto; width: 100%; }

        /* ── Sidebar Open/Closed States ── */
        .sidebar-open .qs { transform: translateX(0); }
        .sidebar-open .qtop { left: 260px; }
        .sidebar-open .qmain-wrapper { margin-left: 260px; }
        .sidebar-closed .qs { transform: translateX(-260px); }
        .sidebar-closed .qtop { left: 0; }
        .sidebar-closed .qmain-wrapper { margin-left: 0; }

        /* Mobile & Tablet Responsiveness */
        @media (max-width: 960px) {
            .qtop, .sidebar-open .qtop, .sidebar-closed .qtop { left: 0 !important; right: 0 !important; padding: 0 16px; height: 64px; }
            .logo-area { width: auto; gap: 8px; }
            .logo-text h1 { font-size: 15px; }
            .logo-text p { display: none; }
            .qmain-wrapper, .sidebar-open .qmain-wrapper, .sidebar-closed .qmain-wrapper { margin-left: 0 !important; padding-top: 64px; }
            .qcontent-container { padding: 16px; }
            .sidebar-open .qs { transform: translateX(0); }
            .sidebar-closed .qs { transform: translateX(-260px); }
        }
        @media (max-width: 640px) {
            .qtop, .sidebar-open .qtop, .sidebar-closed .qtop { padding: 0 12px; height: 58px; }
            .qtop-act { gap: 8px; }
            .qtop-icon-btn { width: 30px; height: 30px; }
            .qtop-profile-pill { padding: 4px; }
            .qtop-profile-pill .name { display: none; }
            .logo-box { width: 30px; height: 30px; }
            .logo-box span { font-size: 16px; }
            .logo-text h1 { font-size: 14px; }
            .qmain-wrapper { padding-top: 58px; }
            .qcontent-container { padding: 12px; }
            .bcard-body { padding: 14px !important; }
            .bph { flex-direction: column; align-items: flex-start; gap: 8px; margin-bottom: 14px; }
            .bstat-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
            .bstat { padding: 12px; gap: 10px; }
            .bstat-icon { width: 38px; height: 38px; }
            .bstat-val { font-size: 20px; }
        }

        /* ── Standard Berry Cards ── */
        .bcard {
            background: var(--bcard);
            border: 1px solid var(--bbor);
            border-radius: 14px;
            box-shadow: 0 2px 14px 0 rgba(32, 40, 45, 0.06);
            overflow: hidden;
            transition: box-shadow 0.25s, transform 0.25s;
        }
        .bcard:hover { box-shadow: 0 6px 20px rgba(33, 150, 243, 0.08); }
        .bcard-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--bbor);
            display: flex; align-items: center; gap: 12px;
            background: var(--bsur);
        }
        .bcard-header-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .bcard-body { padding: 20px; }

        /* ── Stat Cards ── */
        .bstat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        @media (max-width: 1024px) { .bstat-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .bstat-grid { grid-template-columns: 1fr 1fr; gap: 8px; } }

        .bstat {
            background: var(--bcard);
            border: 1px solid var(--bbor);
            border-radius: 12px;
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: 0 2px 14px 0 rgba(32, 40, 45, 0.04);
            transition: all 0.25s;
        }
        .bstat:hover { box-shadow: 0 6px 20px rgba(33, 150, 243, 0.1); transform: translateY(-2px); }
        .bstat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .bstat-val { font-size: 26px; font-weight: 700; color: var(--btxt); line-height: 1; }
        .bstat-lbl { font-size: 12px; color: var(--btxt2); margin-top: 3px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ── Temuan Cards ── */
        .temuan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        @media (max-width: 1024px) { .temuan-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { .temuan-grid { grid-template-columns: 1fr; gap: 12px; } }

        .temuan-card {
            background: var(--bcard);
            border: 1px solid var(--bbor);
            border-radius: 14px;
            overflow: hidden;
            display: flex; flex-direction: column;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.25s; text-decoration: none;
        }
        .temuan-card:hover {
            box-shadow: 0 8px 24px rgba(33, 150, 243, 0.12);
            transform: translateY(-2px);
            border-color: var(--bp);
        }
        .temuan-card-body { padding: 16px; flex: 1; }
        .temuan-card-footer {
            padding: 10px 16px;
            border-top: 1px solid var(--bbor);
            background: var(--bsur);
            display: flex; justify-content: space-between; align-items: center;
            font-size: 12px; color: var(--btxt2);
        }

        /* ── Page Header ── */
        .bph { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .bph-title { font-size: 22px; font-weight: 700; color: var(--btxt); letter-spacing: -0.3px; margin: 0; }
        .bph-sub { font-size: 13px; color: var(--btxt2); margin-top: 4px; font-weight: 500; }

        /* ── Status Badges ── */
        .sbadge {
            display: inline-flex; align-items: center;
            padding: 4px 12px; border-radius: 20px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.4px;
            text-transform: uppercase; border: 1px solid transparent;
        }
        .sbadge-open { background: #fff8e1; color: #b06000; border-color: #fde293; }
        .sbadge-progress { background: var(--bp-light); color: var(--bp-dark); border-color: rgba(33, 150, 243, 0.2); }
        .sbadge-pending { background: #fce8e6; color: #c5221f; border-color: #f2b8b5; }
        .sbadge-closed { background: #e6f4ea; color: #137333; border-color: #ceead6; }
        .dark .sbadge-open { background: rgba(245, 158, 11, 0.18); color: #fde68a; border-color: rgba(245,158,11,0.3); }
        .dark .sbadge-progress { background: rgba(59, 130, 246, 0.18); color: #93c5fd; border-color: rgba(59,130,246,0.3); }
        .dark .sbadge-pending { background: rgba(239, 68, 68, 0.18); color: #fca5a5; border-color: rgba(239,68,68,0.3); }
        .dark .sbadge-closed { background: rgba(16, 185, 129, 0.18); color: #a7f3d0; border-color: rgba(16,185,129,0.3); }

        /* ── Buttons ── */
        .bbtn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 18px; border-radius: 12px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            border: none; transition: all 0.2s;
            text-decoration: none; font-family: inherit;
            white-space: nowrap;
        }
        .bbtn-primary { background: var(--bp); color: #fff; box-shadow: 0 4px 12px rgba(33, 150, 243, 0.25); }
        .bbtn-primary:hover { background: var(--bp-dark); transform: translateY(-1px); }
        .bbtn-secondary { background: var(--bsur); color: var(--btxt); border: 1.5px solid var(--bbor) !important; }
        .bbtn-secondary:hover { background: var(--bp-light); color: var(--bp-dark); }
        .dark .bbtn-secondary { background: #334155; color: #f8fafc; border-color: #475569 !important; }
        .dark .bbtn-secondary:hover { background: #475569; }
        .bbtn-success { background: var(--success); color: #fff; }
        .bbtn-success:hover { background: #00a142; transform: translateY(-1px); }
        .bbtn-danger { background: var(--error); color: #fff; }
        .bbtn-danger:hover { background: #b73214; transform: translateY(-1px); }
        .bbtn-sm { padding: 6px 12px !important; font-size: 12px !important; border-radius: 8px; }
        @media (max-width: 640px) {
            .bbtn { padding: 9px 14px; font-size: 12.5px; border-radius: 8px; }
            .bbtn-sm { width: auto !important; }
        }

        /* ── Form Inputs ── */
        .binput {
            width: 100%; padding: 11px 16px; border: 1.5px solid var(--bbor);
            border-radius: 12px; font-size: 13.5px; color: var(--btxt);
            background: var(--bcard); transition: border-color .2s, box-shadow .2s;
            outline: none; font-family: inherit;
        }
        .binput:focus { border-color: var(--bp); box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.15); }
        .dark .binput { background: #0f172a; color: #f8fafc; border-color: #334155; }
        .dark .binput:focus { border-color: #60a5fa; box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2); }
        .dark .binput:disabled { background: #1e293b; color: #64748b; }
        .binput:disabled { background: var(--bsur); color: var(--btxt2); cursor: not-allowed; }
        .blabel { display: block; font-size: 12px; font-weight: 600; color: var(--btxt2); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.4px; }
        .berr-msg { font-size: 11.5px; color: var(--error); margin-top: 4px; font-weight: 500; }

        /* ── Alerts ── */
        .balert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: 12px; font-size: 13px; border: 1px solid transparent; }
        .balert-success { background: var(--success-light); border-color: rgba(0, 200, 83, 0.2); color: #007d32; }
        .balert-error { background: var(--error-light); border-color: rgba(216, 67, 21, 0.2); color: #b73214; }
        .balert-warn { background: var(--warning-light); border-color: rgba(255, 193, 7, 0.2); color: #b78103; }
        .balert-info { background: var(--bp-light); border-color: rgba(33, 150, 243, 0.2); color: var(--bp-dark); }
        .dark .balert-success { background: rgba(16, 185, 129, 0.15); color: #a7f3d0; border-color: rgba(16, 185, 129, 0.35); }
        .dark .balert-warn { background: rgba(245, 158, 11, 0.15); color: #fde68a; border-color: rgba(245, 158, 11, 0.35); }
        .dark .balert-error { background: rgba(239, 68, 68, 0.15); color: #fca5a5; border-color: rgba(239, 68, 68, 0.35); }
        .dark .balert-info { background: rgba(59, 130, 246, 0.15); color: #93c5fd; border-color: rgba(59, 130, 246, 0.3); }

        /* ── Urgency Banners ── */
        .urgency-overdue { background: #fce8e6; border-bottom: 1px solid #f2b8b5; color: #c5221f; padding: 8px 16px; display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; }
        .urgency-soon { background: #fff8e1; border-bottom: 1px solid #fde293; color: #b06000; padding: 8px 16px; display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; }
        .urgency-pending { background: #f3e8fd; border-bottom: 1px solid #e9d2fc; color: #6b1cb0; padding: 8px 16px; display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; }
        .dark .urgency-overdue { background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #fca5a5; }
        .dark .urgency-soon { background: rgba(245,158,11,0.15); border-color: rgba(245,158,11,0.3); color: #fde68a; }
        .dark .urgency-pending { background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.3); color: #ddd6fe; }

        /* ── Info Field ── */
        .info-field { margin-bottom: 16px; }
        .info-label { font-size: 11px; font-weight: 700; color: var(--btxt2); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; }
        .info-value { font-size: 14px; font-weight: 600; color: var(--btxt); }
        .info-text { font-size: 13.5px; color: var(--btxt); line-height: 1.6; white-space: pre-wrap; background: var(--bsur); padding: 12px 14px; border-radius: 10px; border: 1px solid var(--bbor); }

        /* ── Breadcrumb ── */
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--btxt2); margin-bottom: 20px; }
        .breadcrumb a { color: var(--btxt2); text-decoration: none; transition: color 0.15s; }
        .breadcrumb a:hover { color: var(--bp); }
        .breadcrumb .sep { font-size: 16px; }
        .breadcrumb .current { color: var(--btxt); font-weight: 600; }

        /* ── Animations ── */
        @keyframes fadeUp { from { transform: translateY(12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .fu { animation: fadeUp 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu1 { animation: fadeUp 0.4s 0.05s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu2 { animation: fadeUp 0.4s 0.1s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
        .fu3 { animation: fadeUp 0.4s 0.15s cubic-bezier(0.25, 0.8, 0.25, 1) both; }

        /* ── Misc ── */
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .fil { font-variation-settings: 'FILL' 1; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: window.innerWidth > 960, mobileOpen: false }" :class="sidebarOpen ? 'sidebar-open' : 'sidebar-closed'">

    {{-- ═══ TOP HEADER ═══ --}}
    <header class="qtop">
        <div class="logo-area">
            <div class="logo-box">
                <span class="material-symbols-outlined">factory</span>
            </div>
            <div class="logo-text">
                <h1>SIVERA</h1>
                <p>Verifikasi PRP</p>
            </div>
        </div>

        <div style="display:flex;align-items:center;flex:1;">
            <button @click="if(window.innerWidth > 960) { sidebarOpen = !sidebarOpen } else { mobileOpen = !mobileOpen }" class="qtop-toggle" title="Toggle Sidebar">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>

        <div class="qtop-act">
            <button onclick="toggleTheme()" class="qtop-icon-btn" title="Toggle Theme">
                <span class="material-symbols-outlined" style="font-size:18px;">dark_mode</span>
            </button>
            @auth
            <div class="qtop-profile-pill">
                <div class="qs-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="name">{{ auth()->user()->name }}</span>
            </div>
            @endauth
        </div>
    </header>

    {{-- ═══ SIDEBAR DRAWER ═══ --}}
    <aside class="qs" :style="window.innerWidth <= 960 ? (mobileOpen ? 'transform:translateX(0)' : 'transform:translateX(-260px)') : ''">
        <div class="qs-header">
            <div class="logo-area">
                <div class="logo-box">
                    <span class="material-symbols-outlined">factory</span>
                </div>
                <div class="logo-text">
                    <h1>SIVERA</h1>
                    <p>Version 1.0.0</p>
                </div>
            </div>
        </div>

        <div class="qs-content">
            @auth
                @if(auth()->user()->role === 'pelapor')
                    <span class="qs-group-label">Menu</span>
                    <a class="qs-item {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}" wire:navigate>
                        <span class="material-symbols-outlined ic {{ request()->routeIs('beranda') ? 'fil' : '' }}">home</span>
                        <span>Beranda</span>
                    </a>
                @elseif(auth()->user()->role === 'pic')
                    <span class="qs-group-label">Menu</span>
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
                <button type="submit" class="qs-action">
                    <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                    <span>Sign Out</span>
                </button>
            </form>
            @endauth
        </div>
    </aside>

    {{-- Mobile Backdrop --}}
    <div x-show="mobileOpen" @click="mobileOpen = false" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:40;" x-transition></div>

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
            <div style="width:40px;height:40px;border-radius:10px;background:var(--bp-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span class="material-symbols-outlined" style="color:var(--bp-dark);font-size:22px;">help</span>
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
