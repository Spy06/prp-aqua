<div x-data="{ showForm: false }">

    {{-- Greeting Header --}}
    <div class="bph fu">
        <div>
            @php
                $hour = \Carbon\Carbon::now()->timezone('Asia/Jakarta')->hour;
                $greeting = $hour < 11 ? 'Pagi' : ($hour < 15 ? 'Siang' : ($hour < 18 ? 'Sore' : 'Malam'));
            @endphp
            <h2 class="bph-title">Selamat {{ $greeting }}, {{ auth()->user()->name }}!</h2>
            <p class="bph-sub">Catat observasi perilaku keamanan pangan di area produksi.</p>
        </div>

        @if($tab === 'pelapor')
            <button @click="showForm = !showForm"
                class="bbtn"
                :class="showForm ? 'bbtn-secondary' : 'bbtn-primary'">
                <span class="material-symbols-outlined fil" style="font-size:18px;"
                    x-text="showForm ? 'close' : 'add'"></span>
                <span x-text="showForm ? 'Tutup Form' : 'Catat Observasi Baru'"></span>
            </button>
        @endif
    </div>

    {{-- Tab Switcher --}}
    <div style="display:flex;align-items:center;gap:0;background:var(--bsur);padding:5px;border-radius:12px;border:1px solid var(--bbor);display:inline-flex;margin-bottom:20px;" class="fu1">
        <button wire:click="setTab('pelapor')"
            style="padding:9px 20px;border-radius:8px;font-size:13.5px;font-weight:600;border:none;cursor:pointer;font-family:inherit;transition:all .2s;
            {{ $tab === 'pelapor' ? 'background:var(--bcard);color:var(--bp);box-shadow:0 2px 8px rgba(0,0,0,0.08);' : 'background:transparent;color:var(--btxt2);' }}">
            <span class="material-symbols-outlined {{ $tab === 'pelapor' ? 'fil' : '' }}" style="font-size:16px;vertical-align:-3px;margin-right:4px;">person</span>
            Mode Observer
        </button>

        @if(auth()->user()->role === 'qa')
            <a href="{{ route('bosq.qa.dashboard') }}" wire:navigate
                style="padding:9px 20px;border-radius:8px;font-size:13.5px;font-weight:600;border:none;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;display:flex;align-items:center;gap:6px;background:transparent;color:var(--bs);">
                <span class="material-symbols-outlined" style="font-size:16px;">dashboard</span>
                Dashboard QA
            </a>
        @else
            <button wire:click="setTab('auditee')"
                style="padding:9px 20px;border-radius:8px;font-size:13.5px;font-weight:600;border:none;cursor:pointer;font-family:inherit;transition:all .2s;display:flex;align-items:center;gap:6px;
                {{ $tab === 'auditee' ? 'background:var(--bcard);color:var(--bp);box-shadow:0 2px 8px rgba(0,0,0,0.08);' : 'background:transparent;color:var(--btxt2);' }}">
                <span class="material-symbols-outlined {{ $tab === 'auditee' ? 'fil' : '' }}" style="font-size:16px;">assignment_ind</span>
                Auditee
                @if($auditeeBadge > 0)
                    <span style="background:#c62828;color:#fff;font-size:10.5px;font-weight:700;padding:1px 7px;border-radius:10px;">{{ $auditeeBadge }}</span>
                @else
                    <span style="background:var(--bbor);color:var(--btxt2);font-size:10.5px;font-weight:700;padding:1px 7px;border-radius:10px;">0</span>
                @endif
            </button>
        @endif
    </div>

    {{-- Content --}}
    <div>
        @if($tab === 'pelapor' || auth()->user()->role === 'qa')
            {{-- Form Toggle --}}
            <div x-show="showForm" x-collapse x-cloak style="margin-bottom:24px;">
                <livewire:bos-q.form-temuan />
            </div>
            <livewire:bos-q.daftar-temuan-pelapor />
        @else
            <livewire:bos-q.daftar-temuan-auditee />
        @endif
    </div>
</div>
