<div class="bcard fu" style="max-width:900px;margin:0 auto;box-shadow:0 2px 14px rgba(0,0,0,0.04);">
    <div class="bcard-header" style="justify-content:space-between;border-bottom:1px solid var(--bbor);padding:16px 20px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="bcard-hicon" style="background:var(--bp-light);">
                <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">manage_accounts</span>
            </div>
            <div>
                <div style="font-size:16px;font-weight:700;color:var(--btxt);">Pengaturan Akun</div>
                <div style="font-size:12px;color:var(--btxt2);">Kelola informasi profil dan kata sandi Anda</div>
            </div>
        </div>
    </div>

    <div class="bcard-body" style="padding:20px;">
        <div style="display:flex;gap:24px;" class="max-md:flex-col">
            {{-- Navigation Samping / Tabs --}}
            <div style="width:200px;flex-shrink:0;" class="max-md:w-full">
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <a href="{{ route('profile.edit') }}" wire:navigate
                       style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13.5px;font-weight:600;text-decoration:none;transition:all 0.2s;{{ request()->routeIs('profile.edit') ? 'background:var(--bp-light);color:var(--bp-dark);' : 'color:var(--btxt2);background:var(--bsur);' }}">
                        <span class="material-symbols-outlined" style="font-size:18px;">person</span>
                        Profil
                    </a>
                    <a href="{{ route('security.edit') }}" wire:navigate
                       style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13.5px;font-weight:600;text-decoration:none;transition:all 0.2s;{{ request()->routeIs('security.edit') ? 'background:var(--bp-light);color:var(--bp-dark);' : 'color:var(--btxt2);background:var(--bsur);' }}">
                        <span class="material-symbols-outlined" style="font-size:18px;">lock_reset</span>
                        Ganti Password
                    </a>
                </div>
            </div>

            {{-- Divider Vertikal --}}
            <div style="width:1px;background:var(--bbor);" class="max-md:hidden"></div>

            {{-- Konten Utama --}}
            <div style="flex:1;min-width:0;">
                @if(isset($heading) && $heading)
                    <h3 style="margin:0 0 4px 0;font-size:16px;font-weight:700;color:var(--btxt);">{{ $heading }}</h3>
                @endif
                @if(isset($subheading) && $subheading)
                    <p style="margin:0 0 16px 0;font-size:12.5px;color:var(--btxt2);">{{ $subheading }}</p>
                @endif

                <div style="max-width:540px;">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
