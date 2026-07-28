@php
    $system = session('login_system', 'sivera');
    $homeRoute = $system === 'bosq'
        ? (Auth::user()?->role === 'qa' ? route('bosq.qa.dashboard') : route('bosq.beranda'))
        : (Auth::user()?->role === 'qa' ? route('qa.dashboard') : route('beranda'));
    $homeTitle = $system === 'bosq' ? 'Beranda BOS\'Q' : 'Beranda SIVERA';
@endphp
<div class="breadcrumb fu" style="max-width:900px;margin:0 auto 16px auto;">
    <a href="{{ $homeRoute }}">{{ $homeTitle }}</a>
    <span class="material-symbols-outlined sep" style="font-size:16px;">chevron_right</span>
    <span style="color:var(--btxt);font-weight:600;">Pengaturan Akun</span>
</div>
