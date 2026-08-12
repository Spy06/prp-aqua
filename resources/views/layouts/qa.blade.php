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
    <link rel="icon" type="image/png" href="{{ asset('images/aqua-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
@vite('resources/css/qa.css')
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
                <img src="{{ asset('images/aqua-logo.png') }}" onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/id/3/36/AQUA_Logo_2013.png';" alt="AQUA Logo" style="height: 40px; width: auto; object-fit: contain; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1)); margin-left: 10px;">
                <div style="height: 28px; width: 2px; background-color: #cbd5e1; margin: 0 12px;"></div>
                <div class="logo-text">
                    <h1
                        style="color:#0d47a1; font-size: {{ auth()->user()->isSuperAdmin() ? '16px' : '18px' }}; font-weight: 800; letter-spacing: -0.2px; margin: 0; white-space: nowrap;">
                        {{ auth()->user()->isSuperAdmin() ? "Admin Portal" : "SIVERA" }}</h1>
                </div>
            </div>
        </div>

        <div style="flex:1;"></div>

        <div class="qtop-act">
            {{-- Static User Profile Display --}}
            @auth
                <div class="qtop-profile-pill"
                    style="background:#f3e8ff; border:1px solid rgba(124,58,237,0.2); color:#6b21a8;">
                    <div class="qs-av" style="background:#7c3aed; color:#fff; font-weight:700;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="name"
                        style="font-size:13px; font-weight:600; color:#6b21a8;">{{ auth()->user()->name }}</span>
                </div>
            @endauth
        </div>
    </header>

    {{-- ═══ SIDEBAR DRAWER ═══ --}}
    <aside id="sidebar-drawer" class="qs">
        {{-- Sidebar Logo Area --}}
        <div class="qs-header">
            <div class="logo-area">
                <img src="{{ asset('images/aqua-logo.png') }}" onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/id/3/36/AQUA_Logo_2013.png';" alt="AQUA Logo" style="height: 40px; width: auto; object-fit: contain; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1)); margin-left: 10px;">
                <div style="height: 28px; width: 2px; background-color: #cbd5e1; margin: 0 12px;"></div>
                <div class="logo-text">
                    <h1
                        style="color:#0d47a1; font-size: {{ auth()->user()->isSuperAdmin() ? '16px' : '18px' }}; font-weight: 800; letter-spacing: -0.2px; margin: 0; white-space: nowrap;">
                        {{ auth()->user()->isSuperAdmin() ? "Admin Portal" : "SIVERA" }}</h1>
                </div>
            </div>
            <button id="mobile-menu-close" class="qs-close-btn" aria-label="Close Menu">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Sidebar Menu Content --}}
        <div class="qs-content">
            @if(auth()->user()->isSuperAdmin())
                <span class="qs-group-label" style="color:#059669;font-weight:700;">PUSAT DATA KARYAWAN</span>
                <a class="qs-item {{ request()->routeIs('qa.master.seluruh-karyawan') ? 'active' : '' }}"
                    href="{{ route('qa.master.seluruh-karyawan') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('qa.master.seluruh-karyawan') ? 'fil' : '' }}">badge</span>
                    <span>Master Data Karyawan</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.akun') ? 'active' : '' }}"
                    href="{{ route('qa.master.akun') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('qa.master.akun') ? 'fil' : '' }}">manage_accounts</span>
                    <span>Manajemen Akun User</span>
                </a>

                <span class="qs-group-label" style="margin-top:16px;color:#7c3aed;font-weight:700;">MASTER SIVERA</span>
                <a class="qs-item {{ request()->routeIs('qa.master.karyawan') ? 'active' : '' }}"
                    href="{{ route('qa.master.karyawan') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('qa.master.karyawan') ? 'fil' : '' }}">group</span>
                    <span>Master PIC</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.departemen') ? 'active' : '' }}"
                    href="{{ route('qa.master.departemen') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('qa.master.departemen') ? 'fil' : '' }}">domain</span>
                    <span>Master Departemen</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.master.klausul') ? 'active' : '' }}"
                    href="{{ route('qa.master.klausul') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('qa.master.klausul') ? 'fil' : '' }}">rule</span>
                    <span>Klausul PRP</span>
                </a>

                <span class="qs-group-label" style="margin-top:16px;color:#1976d2;font-weight:700;">MASTER BOS'Q</span>
                <a class="qs-item {{ request()->routeIs('bosq.qa.master.line') ? 'active' : '' }}"
                    href="{{ route('bosq.qa.master.line') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.master.line') ? 'fil' : '' }}">badge</span>
                    <span>PIC Sub Area</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.master.subarea') ? 'active' : '' }}"
                    href="{{ route('bosq.qa.master.subarea') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.master.subarea') ? 'fil' : '' }}">location_on</span>
                    <span>Master Sub Area</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.master.elemen') ? 'active' : '' }}"
                    href="{{ route('bosq.qa.master.elemen') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.master.elemen') ? 'fil' : '' }}">category</span>
                    <span>Elemen QFS</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.master.karyawan') ? 'active' : '' }}"
                    href="{{ route('bosq.qa.master.karyawan') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.master.karyawan') ? 'fil' : '' }}">groups</span>
                    <span>Divisi Manajemen</span>
                </a>

                <span class="qs-group-label" style="margin-top:16px;color:var(--btxt2);font-weight:700;">MONITORING & REKAP
                    SIVERA</span>
                <a class="qs-item {{ request()->routeIs('qa.dashboard') ? 'active' : '' }}"
                    href="{{ route('qa.dashboard') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('qa.dashboard') ? 'fil' : '' }}">bar_chart</span>
                    <span>Grafik Temuan</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.daftar-temuan') ? 'active' : '' }}"
                    href="{{ route('qa.daftar-temuan') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('qa.daftar-temuan') ? 'fil' : '' }}">list_alt</span>
                    <span>Daftar Temuan</span>
                </a>
                <a class="qs-item {{ request()->routeIs('qa.rekap') ? 'active' : '' }}" href="{{ route('qa.rekap') }}"
                    wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('qa.rekap') ? 'fil' : '' }}">calendar_month</span>
                    <span>Rekap Periode</span>
                </a>

                <span class="qs-group-label" style="margin-top:16px;color:var(--btxt2);font-weight:700;">MONITORING
                    BOS'Q</span>
                <a class="qs-item {{ request()->routeIs('bosq.qa.dashboard') ? 'active' : '' }}"
                    href="{{ route('bosq.qa.dashboard') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.dashboard') ? 'fil' : '' }}">dashboard</span>
                    <span>Grafik Temuan BOS'Q</span>
                </a>
                <a class="qs-item {{ request()->routeIs('bosq.qa.daftar-observasi') ? 'active' : '' }}"
                    href="{{ route('bosq.qa.daftar-observasi') }}" wire:navigate>
                    <span
                        class="material-symbols-outlined ic {{ request()->routeIs('bosq.qa.daftar-observasi') ? 'fil' : '' }}">list_alt</span>
                    <span>Daftar Observasi BOS'Q</span>
                </a>
            @else
                @php
                    $isPicUser = auth()->check() && auth()->user()->isSiveraPicUser();
                @endphp

                @if(auth()->user()->role === 'qa')
                    <span class="qs-group-label">Dashboard QA</span>
                    <a class="qs-item {{ request()->routeIs('qa.dashboard') ? 'active' : '' }}"
                        href="{{ route('qa.dashboard') }}" wire:navigate>
                        <span
                            class="material-symbols-outlined ic {{ request()->routeIs('qa.dashboard') ? 'fil' : '' }}">bar_chart</span>
                        <span>Grafik Temuan</span>
                    </a>
                    @php $pendingQaCount = \App\Models\Temuan::where('status', 'closed_pending_qa')->count(); @endphp
                    <a class="qs-item {{ request()->routeIs('qa.daftar-temuan') ? 'active' : '' }}"
                        href="{{ route('qa.daftar-temuan') }}" wire:navigate style="justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <span
                                class="material-symbols-outlined ic {{ request()->routeIs('qa.daftar-temuan') ? 'fil' : '' }}">list_alt</span>
                            <span>Daftar Temuan</span>
                        </div>
                        @if($pendingQaCount > 0)
                            <span
                                style="background:var(--error, #ef4444);color:white;font-size:11px;font-weight:700;padding:2px 6px;border-radius:10px;line-height:1;">{{ $pendingQaCount }}</span>
                        @endif
                    </a>
                    <a class="qs-item {{ request()->routeIs('qa.rekap') ? 'active' : '' }}" href="{{ route('qa.rekap') }}"
                        wire:navigate>
                        <span
                            class="material-symbols-outlined ic {{ request()->routeIs('qa.rekap') ? 'fil' : '' }}">calendar_month</span>
                        <span>Rekap Periode</span>
                    </a>

                    <span class="qs-group-label" style="margin-top:16px;">Mode Pelapor</span>
                    <a class="qs-item {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}"
                        wire:navigate>
                        <span
                            class="material-symbols-outlined ic {{ request()->routeIs('beranda') ? 'fil' : '' }}">add_a_photo</span>
                        <span>Lapor Temuan Saya</span>
                    </a>

                    <span class="qs-group-label" style="margin-top:16px;">Master Data</span>
                    <a class="qs-item {{ request()->routeIs('qa.master.karyawan') ? 'active' : '' }}"
                        href="{{ route('qa.master.karyawan') }}" wire:navigate>
                        <span class="material-symbols-outlined ic">badge</span>
                        <span>Master PIC</span>
                    </a>
                    <a class="qs-item {{ request()->routeIs('qa.master.departemen') ? 'active' : '' }}"
                        href="{{ route('qa.master.departemen') }}" wire:navigate>
                        <span class="material-symbols-outlined ic">domain</span>
                        <span>Master Departemen</span>
                    </a>
                    <a class="qs-item {{ request()->routeIs('qa.master.klausul') ? 'active' : '' }}"
                        href="{{ route('qa.master.klausul') }}" wire:navigate>
                        <span class="material-symbols-outlined ic">rule</span>
                        <span>Klausul PRP</span>
                    </a>

                    <span class="qs-group-label" style="margin-top:16px;color:#7c3aed;font-weight:700;">BERALIH SISTEM</span>
                    <a class="qs-item" href="{{ route('bosq.qa.dashboard') }}" wire:navigate>
                        <span class="material-symbols-outlined ic" style="color:#7c3aed;">swap_horiz</span>
                        <span>Beralih ke System BOS'Q</span>
                    </a>
                @elseif($isPicUser)
                    <span class="qs-group-label">Monitoring & Analytics</span>
                    <a class="qs-item {{ request()->routeIs('qa.dashboard') ? 'active' : '' }}"
                        href="{{ route('qa.dashboard') }}" wire:navigate>
                        <span
                            class="material-symbols-outlined ic {{ request()->routeIs('qa.dashboard') ? 'fil' : '' }}">bar_chart</span>
                        <span>Grafik Temuan</span>
                    </a>
                    <a class="qs-item {{ request()->routeIs('qa.daftar-temuan') ? 'active' : '' }}"
                        href="{{ route('qa.daftar-temuan') }}" wire:navigate>
                        <span
                            class="material-symbols-outlined ic {{ request()->routeIs('qa.daftar-temuan') ? 'fil' : '' }}">list_alt</span>
                        <span>Daftar Temuan</span>
                    </a>

                    <span class="qs-group-label" style="margin-top:16px;">Pelapor & PIC</span>
                    <a class="qs-item {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}"
                        wire:navigate>
                        <span
                            class="material-symbols-outlined ic {{ request()->routeIs('beranda') ? 'fil' : '' }}">add_a_photo</span>
                        <span>Lapor & Tindak Lanjut</span>
                    </a>

                    <span class="qs-group-label" style="margin-top:16px;color:#7c3aed;font-weight:700;">BERALIH SISTEM</span>
                    <a class="qs-item" href="{{ route('bosq.beranda') }}" wire:navigate>
                        <span class="material-symbols-outlined ic" style="color:#7c3aed;">swap_horiz</span>
                        <span>Beralih ke System BOS'Q</span>
                    </a>
                @else
                    <span class="qs-group-label">Pelapor</span>
                    <a class="qs-item {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}"
                        wire:navigate>
                        <span
                            class="material-symbols-outlined ic {{ request()->routeIs('beranda') ? 'fil' : '' }}">add_a_photo</span>
                        <span>Lapor Temuan Saya</span>
                    </a>

                    <span class="qs-group-label" style="margin-top:16px;color:#7c3aed;font-weight:700;">BERALIH SISTEM</span>
                    <a class="qs-item" href="{{ route('bosq.beranda') }}" wire:navigate>
                        <span class="material-symbols-outlined ic" style="color:#7c3aed;">swap_horiz</span>
                        <span>Beralih ke System BOS'Q</span>
                    </a>
                @endif
            @endif
        </div>

        {{-- Sidebar Footer with logout and user detail --}}
        <div class="qs-footer">
            @auth
                <div class="qs-user">
                    <div class="qs-av" style="{{ auth()->user()->isSuperAdmin() ? 'background:#7c3aed;' : '' }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div style="overflow:hidden;flex:1;">
                        <div class="qs-uname truncate" style="color:var(--btxt);font-size:13px;font-weight:600;">
                            {{ auth()->user()->name }}</div>
                        @php
                            $displayRole = 'Karyawan';
                            if (auth()->user()->isSuperAdmin()) {
                                $displayRole = 'Super Admin';
                            } elseif (auth()->user()->role === 'qa') {
                                $displayRole = 'QA Admin';
                            } else {
                                $isPicUser = auth()->user()->isSiveraPicUser();
                                $dept = auth()->user()->karyawan?->departemen?->nama_departemen;
                                if ($isPicUser) {
                                    $displayRole = 'PIC' . ($dept ? " ({$dept})" : '');
                                } else {
                                    $displayRole = $dept ?: 'Karyawan';
                                }
                            }
                        @endphp
                        <div class="qs-urole" style="color:var(--btxt2);font-size:11px;">{{ $displayRole }}</div>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="qs-action"
                    style="margin-bottom: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
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
        <main class="qcontent-container" style="flex:1;">
            {{ $slot }}
        </main>
        <footer style="padding:16px 32px;text-align:right;font-size:12px;color:var(--btxt2);border-top:1px solid var(--bbor);margin-top:auto;background:var(--bsur);opacity:0.85;">
            © 2026 PT Tirta Investama — Plant Cianjur. <a href="https://github.com/FahriID563" target="_blank" rel="noopener noreferrer" style="color:inherit;text-decoration:none;opacity:0.75;transition:opacity 0.2s;" onmouseover="this.style.opacity='1';this.style.textDecoration='underline'" onmouseout="this.style.opacity='0.75';this.style.textDecoration='none'">Built by @FahriID563</a>. All Rights Reserved.
        </footer>
    </div>

    {{-- ═══ Berry Custom Confirm Dialog ═══ --}}
    <dialog id="custom-confirm-modal"
        style="position:fixed;inset:0;margin:auto;border-radius:16px;border:1px solid var(--bbor);background:var(--bcard);padding:24px;max-width:380px;width:90%;color:var(--btxt);outline:none;box-shadow:0 24px 60px rgba(0,0,0,.15);"
        class="backdrop:bg-black/40 backdrop:backdrop-blur-sm">
        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;">
            <div
                style="width:40px;height:40px;border-radius:10px;background:var(--bs-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span class="material-symbols-outlined" style="color:var(--bs-dark);font-size:22px;">help</span>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;margin:0 0 6px;color:var(--btxt);">Konfirmasi Aksi</h3>
                <p id="custom-confirm-message" style="font-size:13px;color:var(--btxt2);margin:0;line-height:1.5;">
                    Apakah Anda yakin?</p>
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
                const msgEl = document.getElementById('custom-confirm-message');
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
