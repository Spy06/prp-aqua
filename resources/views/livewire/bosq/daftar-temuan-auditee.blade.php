<div>
    {{-- Stat Cards --}}
    @php
        $myTemuans = \App\Models\BosqTemuan::where('auditee_id', auth()->id())->where('dampak_temuan', 'negatif');
        $metrics = [
            'open'       => (clone $myTemuans)->where('status', 'open')->count(),
            'in_progress'=> (clone $myTemuans)->where('status', 'in_progress')->count(),
            'pending_qa' => (clone $myTemuans)->where('status', 'closed_pending_qa')->count(),
            'closed'     => (clone $myTemuans)->where('status', 'closed_acc')->count(),
        ];
    @endphp
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;" class="fu">
        <div class="bstat">
            <div class="bstat-icon" style="background:#fff3e0;">
                <span class="material-symbols-outlined fil" style="color:#e65100;font-size:22px;">assignment_late</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#e65100;">{{ $metrics['open'] }}</div>
                <div class="bstat-lbl">Open</div>
            </div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:22px;">pending</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#1565c0;">{{ $metrics['in_progress'] }}</div>
                <div class="bstat-lbl">In Progress</div>
            </div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:#f3e5f5;">
                <span class="material-symbols-outlined fil" style="color:#6a1b9a;font-size:22px;">hourglass_top</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#6a1b9a;">{{ $metrics['pending_qa'] }}</div>
                <div class="bstat-lbl">Pending QA</div>
            </div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:22px;">task_alt</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#2e7d32;">{{ $metrics['closed'] }}</div>
                <div class="bstat-lbl">Closed</div>
            </div>
        </div>
    </div>

    {{-- Page Header --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Observasi yang Saya Terima</h2>
            <p class="bph-sub">Temuan yang Anda perlu tindak lanjuti sebagai Auditee</p>
        </div>
    </div>

    {{-- Cards --}}
    @if($temuans->isEmpty())
        <div class="bcard fu2" style="padding:52px;text-align:center;">
            <span class="material-symbols-outlined" style="font-size:48px;color:var(--btxt2);opacity:0.4;display:block;margin-bottom:12px;">assignment_turned_in</span>
            <div style="font-size:15px;font-weight:600;color:var(--btxt);margin-bottom:6px;">Tidak ada observasi aktif</div>
            <p style="font-size:13px;color:var(--btxt2);">Saat ini tidak ada observasi yang perlu Anda tindak lanjuti.</p>
        </div>
    @else
        <div class="tcard-grid fu2">
            @foreach($temuans as $temuan)
                @php
                    $statusClass = match($temuan->status) {
                        'open'              => 'sbadge-open',
                        'in_progress'       => 'sbadge-progress',
                        'closed_pending_qa' => 'sbadge-pending',
                        'closed_acc'        => 'sbadge-closed',
                        default             => 'sbadge-progress',
                    };
                    $statusText = match($temuan->status) {
                        'open'              => 'Open',
                        'in_progress'       => 'In Progress',
                        'closed_pending_qa' => 'Pending QA',
                        'closed_acc'        => 'Closed (ACC)',
                        default             => $temuan->status,
                    };
                    $risikoColor = match($temuan->tingkat_resiko) {
                        'food_safety_risk'   => '#c62828',
                        'major_quality_risk' => '#e65100',
                        default              => '#1565c0',
                    };
                @endphp

                <a href="{{ route('bosq.temuan.detail', $temuan->id) }}" class="tcard">
                    <div class="tcard-body">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px;">
                            <h3 class="tcard-dept">{{ $temuan->departemen->nama_departemen ?? '-' }}</h3>
                            <span class="sbadge {{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                        <div class="tcard-sub">
                            <span class="material-symbols-outlined" style="font-size:14px;">location_on</span>
                            {{ $temuan->line->nama_line ?? '-' }} / {{ $temuan->subArea->nama_sub_area ?? '-' }}
                        </div>
                        <p class="tcard-desc">{{ $temuan->elemenQfs->nama_elemen ?? '-' }}</p>
                        @if($tl = $temuan->tindakLanjut)
                            @if($tl->due_date)
                                @php $isDue = \Carbon\Carbon::parse($tl->due_date)->isPast(); @endphp
                                <div style="margin-top:6px;font-size:12px;font-weight:600;color:{{ $isDue ? '#c62828' : '#1565c0' }};display:flex;align-items:center;gap:4px;">
                                    <span class="material-symbols-outlined" style="font-size:14px;">schedule</span>
                                    Due: {{ \Carbon\Carbon::parse($tl->due_date)->format('d M Y') }}
                                    @if($isDue) <span style="color:#c62828;">(Terlambat!)</span> @endif
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="tcard-footer">
                        <div style="display:flex;align-items:center;gap:5px;">
                            <span class="material-symbols-outlined" style="font-size:14px;">calendar_today</span>
                            {{ $temuan->tanggal_temuan->format('d M Y') }}
                        </div>
                        <div style="display:flex;align-items:center;gap:5px;">
                            <span class="material-symbols-outlined" style="font-size:14px;">person</span>
                            <span class="truncate" style="max-width:110px;">{{ $temuan->pelapor->name ?? '-' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:20px;">
            {{ $temuans->links('vendor.pagination.tailwind') }}
        </div>
    @endif

    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns:repeat(4"] { grid-template-columns: repeat(2,1fr) !important; gap: 10px !important; }
        }
    </style>
</div>
