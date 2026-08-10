@php
    $system = session('login_system', 'sivera');
    if (Auth::user()?->isSuperAdmin()) {
        $homeRoute = route('qa.master.akun');
        $homeTitle = 'Manajemen Akun User';
    } else {
        $homeRoute = $system === 'bosq'
            ? (Auth::user()?->role === 'qa' ? route('bosq.qa.dashboard') : route('bosq.beranda'))
            : (Auth::user()?->role === 'qa' ? route('qa.dashboard') : route('beranda'));
        $homeTitle = $system === 'bosq' ? 'Beranda BOS\'Q' : 'Beranda SIVERA';
    }
@endphp
<div style="max-width:900px;margin:0 auto 16px auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div class="breadcrumb" style="margin:0;">
        <a href="{{ $homeRoute }}">{{ $homeTitle }}</a>
        <span class="material-symbols-outlined sep" style="font-size:16px;">chevron_right</span>
        <span style="color:var(--btxt);font-weight:600;">Pengaturan Akun</span>
    </div>
    <a href="{{ $homeRoute }}" class="bbtn bbtn-secondary bbtn-sm" style="text-decoration:none;">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
        Kembali ke {{ $homeTitle }}
    </a>
</div>
