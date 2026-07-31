<div>
    {{-- Stat Cards --}}
    @php
        $myTemuans = \App\Models\BosqTemuan::where('pelapor_id', auth()->id());
        $metrics = [
            'total'  => (clone $myTemuans)->count(),
            'open'   => (clone $myTemuans)->whereIn('status', ['open', 'in_progress', 'closed_pending_qa'])->count(),
            'closed' => (clone $myTemuans)->whereIn('status', ['closed', 'closed_acc'])->count(),
        ];
    @endphp
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px;" class="fu">
        {{-- Card Stat Open --}}
        <div wire:click="setFilterStatus('open')" class="bstat" style="cursor:pointer;user-select:none;transition:all 0.2s ease;{{ $filterStatus === 'open' ? 'border:2px solid #e65100;background:#fff3e0;' : '' }}">
            <div class="bstat-icon" style="background:#fff3e0;">
                <span class="material-symbols-outlined fil" style="color:#e65100;font-size:24px;">error</span>
            </div>
            <div style="flex:1;">
                <div class="bstat-val" style="color:#e65100;">{{ $metrics['open'] }}</div>
                <div class="bstat-lbl">Open</div>
            </div>
            @if($filterStatus === 'open')
                <span class="material-symbols-outlined" style="color:#e65100;font-size:20px;">check_circle</span>
            @endif
        </div>

        {{-- Card Stat Closed --}}
        <div wire:click="setFilterStatus('closed')" class="bstat" style="cursor:pointer;user-select:none;transition:all 0.2s ease;{{ $filterStatus === 'closed' ? 'border:2px solid #2e7d32;background:#e8f5e9;' : '' }}">
            <div class="bstat-icon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:24px;">task_alt</span>
            </div>
            <div style="flex:1;">
                <div class="bstat-val" style="color:#2e7d32;">{{ $metrics['closed'] }}</div>
                <div class="bstat-lbl">Closed (Diverifikasi QA)</div>
            </div>
            @if($filterStatus === 'closed')
                <span class="material-symbols-outlined" style="color:#2e7d32;font-size:20px;">check_circle</span>
            @endif
        </div>
    </div>

    {{-- Page Header & Status Filter Pills --}}
    <div class="bph fu1" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
        <div>
            <h2 class="bph-title">Observasi yang Saya Catat</h2>
            <p class="bph-sub">Semua observasi BOS'Q yang pernah Anda laporkan</p>
        </div>

        {{-- Filter Buttons: Semua | Open | Closed --}}
        <div style="display:flex;align-items:center;gap:8px;background:var(--bsur, #f4f4f5);padding:4px;border-radius:10px;border:1px solid var(--bbor, #e4e4e7);">
            <button type="button" wire:click="setFilterStatus('')"
                style="padding:6px 14px;border-radius:8px;font-size:12.5px;font-weight:600;border:none;cursor:pointer;transition:all .15s;font-family:inherit;{{ $filterStatus === '' ? 'background:var(--bp-dark, #1565c0);color:#ffffff;box-shadow:0 2px 6px rgba(0,0,0,0.12);' : 'background:transparent;color:var(--btxt2, #71717a);' }}">
                Semua ({{ $metrics['total'] }})
            </button>

            <button type="button" wire:click="setFilterStatus('open')"
                style="padding:6px 14px;border-radius:8px;font-size:12.5px;font-weight:600;border:none;cursor:pointer;transition:all .15s;font-family:inherit;{{ $filterStatus === 'open' ? 'background:#e65100;color:#ffffff;box-shadow:0 2px 6px rgba(230,81,0,0.25);' : 'background:transparent;color:var(--btxt2, #71717a);' }}">
                Open ({{ $metrics['open'] }})
            </button>

            <button type="button" wire:click="setFilterStatus('closed')"
                style="padding:6px 14px;border-radius:8px;font-size:12.5px;font-weight:600;border:none;cursor:pointer;transition:all .15s;font-family:inherit;{{ $filterStatus === 'closed' ? 'background:#2e7d32;color:#ffffff;box-shadow:0 2px 6px rgba(46,125,50,0.25);' : 'background:transparent;color:var(--btxt2, #71717a);' }}">
                Closed ({{ $metrics['closed'] }})
            </button>
        </div>
    </div>

    {{-- Cards Grid --}}
    @if($temuans->isEmpty())
        <div class="bcard fu2" style="padding:48px;text-align:center;">
            <span class="material-symbols-outlined" style="font-size:48px;color:var(--btxt2);opacity:0.4;display:block;margin-bottom:12px;">inbox</span>
            <div style="font-size:15px;font-weight:600;color:var(--btxt);margin-bottom:6px;">
                {{ $filterStatus !== '' ? 'Tidak ada observasi dengan status ' . strtoupper($filterStatus) : 'Belum ada observasi' }}
            </div>
            <p style="font-size:13px;color:var(--btxt2);">
                {{ $filterStatus !== '' ? 'Klik "Semua" untuk melihat seluruh daftar observasi yang pernah Anda laporkan.' : 'Mulai dengan menekan tombol "Catat Observasi Baru" di atas.' }}
            </p>
        </div>
    @else
        <div class="tcard-grid fu2">
            @foreach($temuans as $temuan)
                @php
                    $isClosed    = in_array($temuan->status, ['closed', 'closed_acc']);
                    $statusClass = $isClosed ? 'sbadge-closed' : 'sbadge-open';
                    $statusText  = $isClosed ? 'Closed' : 'Open';
                    $risikoColor = match($temuan->tingkat_resiko) {
                        'food_safety_risk'   => '#c62828',
                        'major_quality_risk' => '#e65100',
                        default              => '#1565c0',
                    };
                    $risikoLabel = match($temuan->tingkat_resiko) {
                        'food_safety_risk'   => 'FSR',
                        'major_quality_risk' => 'MQR',
                        default              => 'MQR',
                    };
                @endphp

                <a href="{{ route('bosq.temuan.detail', $temuan->id) }}" class="tcard">
                    <div class="tcard-body">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px;">
                            <h3 class="tcard-dept">{{ $temuan->departemen->nama_departemen ?? '-' }}</h3>
                            <div style="display:flex;gap:5px;align-items:center;">
                                <span style="font-size:10px;font-weight:700;padding:2px 6px;border-radius:6px;background:{{ $risikoColor }}22;color:{{ $risikoColor }};border:1px solid {{ $risikoColor }}44;text-transform:uppercase;">{{ $risikoLabel }}</span>
                                <span class="sbadge {{ $statusClass }}">{{ $statusText }}</span>
                            </div>
                        </div>
                        <div class="tcard-sub">
                            <span class="material-symbols-outlined" style="font-size:14px;">location_on</span>
                            {{ $temuan->subArea->nama_sub_area ?? '-' }}
                            @if($temuan->detail_sub_area)
                                <span> — {{ $temuan->detail_sub_area }}</span>
                            @endif
                        </div>
                        <p class="tcard-desc">{{ $temuan->elemenQfs->nama_elemen ?? '-' }}</p>
                        <div style="display:flex;align-items:center;gap:6px;margin-top:6px;">
                            @if($temuan->dampak_temuan === 'positif')
                                <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px;background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;">✓ Positif</span>
                            @else
                                <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px;background:#ffebee;color:#c62828;border:1px solid #ef9a9a;">⚠ Negatif</span>
                            @endif
                        </div>
                    </div>
                    <div class="tcard-footer">
                        <div style="display:flex;align-items:center;gap:5px;">
                            <span class="material-symbols-outlined" style="font-size:14px;">calendar_today</span>
                            {{ $temuan->tanggal_temuan->format('d M Y') }}
                        </div>
                        <div style="display:flex;align-items:center;gap:5px;">
                            <span class="material-symbols-outlined" style="font-size:14px;">person</span>
                            <span class="truncate" style="max-width:110px;">{{ $temuan->auditee->name ?? '-' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:20px;">
            {{ $temuans->links() }}
        </div>
    @endif

    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns:repeat(2"] { grid-template-columns: repeat(2,1fr) !important; gap: 10px !important; }
        }
    </style>
</div>
