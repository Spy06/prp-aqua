<x-layouts::bosq :title="$title">
    <div class="bph fu">
        <div>
            <h2 class="bph-title">{{ $title }}</h2>
            <p class="bph-sub">{{ $desc }}</p>
        </div>
    </div>

    <div class="bcard fu1" style="padding:48px;text-align:center;">
        <div style="width:64px;height:64px;border-radius:16px;background:var(--bp-light);color:var(--bp-dark);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <span class="material-symbols-outlined" style="font-size:36px;">construction</span>
        </div>
        <h3 style="font-size:18px;font-weight:700;color:var(--btxt);margin:0 0 8px;">{{ $title }}</h3>
        <p style="font-size:14px;color:var(--btxt2);max-width:480px;margin:0 auto 20px;">
            {{ $desc }}. Modul ini dijadwalkan untuk dikembangkan pada Hari 3.
        </p>
        <a href="{{ route('bosq.beranda') }}" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Kembali ke Beranda BOS'Q
        </a>
    </div>
</x-layouts::bosq>

