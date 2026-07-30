<div x-data="{ showForm: false }">

    {{-- Greeting Header --}}
    <div class="bph fu">
        <div>
            @php
                $hour = \Carbon\Carbon::now()->timezone('Asia/Jakarta')->hour;
                $greeting = $hour < 11 ? 'Pagi' : ($hour < 15 ? 'Siang' : ($hour < 18 ? 'Sore' : 'Malam'));
            @endphp
            <h2 class="bph-title">Selamat {{ $greeting }}, {{ auth()->user()->name }}!</h2>
            <p class="bph-sub">Catat observasi perilaku keamanan pangan (QFS) di area produksi.</p>
        </div>

        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            @if(auth()->user()->role === 'qa')
                <a href="{{ route('bosq.qa.dashboard') }}" wire:navigate class="bbtn bbtn-secondary">
                    <span class="material-symbols-outlined" style="font-size:18px;">dashboard</span>
                    Dashboard QA
                </a>
            @endif

            <button x-on:click="showForm = !showForm" class="bbtn" :class="showForm ? 'bbtn-secondary' : 'bbtn-primary'">
                <span class="material-symbols-outlined fil" style="font-size:18px;"
                    x-text="showForm ? 'close' : 'add'"></span>
                <span x-text="showForm ? 'Tutup Form' : 'Catat Observasi Baru'"></span>
            </button>
        </div>
    </div>

    {{-- Form Toggle (z-index tinggi agar dropdown melayang di atas daftar observasi) --}}
    <div x-show="showForm" style="margin-bottom:24px; position:relative; z-index:50;">
        <livewire:bos-q.form-temuan />
    </div>

    {{-- Daftar Observasi --}}
    <div style="position:relative; z-index:1;">
        <livewire:bos-q.daftar-temuan-pelapor />
    </div>
</div>