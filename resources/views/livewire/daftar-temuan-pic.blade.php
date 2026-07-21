<div>
    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;" class="fu">
        <div class="bstat">
            <div class="bstat-icon" style="background:#fff3e0;">
                <span class="material-symbols-outlined fil" style="color:#e65100;font-size:22px;">error</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#e65100;">{{ $metrics['open'] ?? 0 }}</div>
                <div class="bstat-lbl">Open</div>
            </div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:22px;">pending</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#1565c0;">{{ $metrics['in_progress'] ?? 0 }}</div>
                <div class="bstat-lbl">In Progress</div>
            </div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:#f3e5f5;">
                <span class="material-symbols-outlined fil" style="color:#6a1b9a;font-size:22px;">hourglass_top</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#6a1b9a;">{{ $metrics['pending_qa'] ?? 0 }}</div>
                <div class="bstat-lbl">Pending QA</div>
            </div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:22px;">task_alt</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#2e7d32;">{{ $metrics['closed'] ?? 0 }}</div>
                <div class="bstat-lbl">Closed</div>
            </div>
        </div>
    </div>

    {{-- Page Header --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Temuan Saya (PIC)</h2>
            <p class="bph-sub">Temuan yang menunggu tindak lanjut Anda</p>
        </div>
    </div>

    {{-- Temuan Cards --}}
    @if($temuans->isEmpty())
        <div class="bcard fu2" style="padding:52px;text-align:center;">
            <div style="width:56px;height:56px;margin:0 auto 14px;border-radius:50%;background:#e8f5e9;display:flex;align-items:center;justify-content:center;">
                <span class="material-symbols-outlined fil" style="font-size:28px;color:#2e7d32;">check_circle</span>
            </div>
            <div style="font-size:15px;font-weight:600;color:var(--btxt);margin-bottom:6px;">Semua selesai!</div>
            <p style="font-size:13px;color:var(--btxt2);">Tidak ada temuan yang perlu ditindaklanjuti saat ini.</p>
        </div>
    @else
        <div class="tcard-grid fu2">
            @foreach($temuans as $temuan)
                @php
                    $tl = $temuan->tindakLanjut;
                    $dueDate = $tl?->due_date;
                    $isOverdue  = $dueDate && $dueDate->lt($today) && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);
                    $isDueSoon  = $dueDate && !$isOverdue && $today->diffInDays($dueDate, false) <= 3 && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);
                    $isPendingQa = $temuan->status === 'closed_pending_qa';

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

                    $borderStyle = '';
                    if ($isOverdue) $borderStyle = 'border-left: 4px solid #c62828;';
                    elseif ($isDueSoon) $borderStyle = 'border-left: 4px solid #e65100;';
                    elseif ($isPendingQa) $borderStyle = 'border-left: 4px solid #6a1b9a;';
                @endphp

                <a href="{{ route('temuan.detail', $temuan->id) }}" class="tcard" style="{{ $borderStyle }}">

                    {{-- Urgency Banner --}}
                    @if($isOverdue)
                        <div class="urgency-overdue">
                            <span class="material-symbols-outlined fil" style="font-size:16px;">warning</span>
                            OVERDUE — {{ $dueDate->format('d M Y') }}
                        </div>
                    @elseif($isDueSoon)
                        <div class="urgency-soon">
                            <span class="material-symbols-outlined fil" style="font-size:16px;">schedule</span>
                            Due {{ $dueDate->diffForHumans() }}
                        </div>
                    @elseif($isPendingQa)
                        <div class="urgency-pending">
                            <span class="material-symbols-outlined fil" style="font-size:16px;">shield</span>
                            Sedang ditinjau QA
                        </div>
                    @endif

                    <div class="tcard-body">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px;">
                            <h3 class="tcard-dept">{{ $temuan->departemen->nama_departemen ?? '-' }}</h3>
                            <span class="sbadge {{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                        <div class="tcard-sub">
                            <span class="material-symbols-outlined" style="font-size:14px;">location_on</span>
                            Sub area: <strong>{{ $temuan->sub_area }}</strong>
                            @if($temuan->sub_area === 'Others' && $temuan->detail_sub_area)
                                <span> — {{ $temuan->detail_sub_area }}</span>
                            @endif
                        </div>
                        <p class="tcard-desc">{{ $temuan->deskripsi }}</p>

                        @if($temuan->klausul)
                            <div style="margin-top:10px;">
                                <span style="display:inline-block;padding:3px 10px;font-size:10.5px;font-weight:700;background:var(--bsur);color:var(--btxt2);border-radius:6px;border:1px solid var(--bbor);">
                                    {{ $temuan->klausul->kode_klausul }}: {{ Str::limit($temuan->klausul->nama_klausul, 30) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="tcard-footer">
                        <div style="display:flex;align-items:center;gap:5px;">
                            <span class="material-symbols-outlined" style="font-size:14px;">calendar_today</span>
                            {{ $temuan->tanggal_temuan->format('d M Y') }}
                        </div>
                        <div style="display:flex;align-items:center;gap:5px;">
                            <span style="color:var(--btxt2);">dari:</span>
                            <span class="truncate" style="max-width:90px;font-weight:600;color:var(--btxt);">{{ $temuan->pelapor->name ?? '-' }}</span>
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
        @media (max-width: 420px) {
            div[style*="grid-template-columns:repeat(4"] { grid-template-columns: 1fr 1fr !important; }
        }
    </style>
</div>
