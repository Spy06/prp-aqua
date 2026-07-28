<div style="max-width:900px;margin:0 auto;" id="bosq-detail-container" x-data="{ lightboxOpen: false, lightboxSrc: '', lightboxTitle: '' }">

    {{-- Breadcrumb --}}
    <div class="breadcrumb fu">
        <a href="{{ route('bosq.beranda') }}">BOS'Q Beranda</a>
        <span class="material-symbols-outlined sep" style="font-size:16px;">chevron_right</span>
        <span style="color:var(--btxt);font-weight:600;">Observasi #{{ $temuan->id }}</span>
    </div>

    {{-- Card: Info Observasi --}}
    <div class="bcard fu1" style="margin-bottom:20px;">
        <div class="bcard-header" style="justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="bcard-hicon" style="background:#e3f2fd;">
                    <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:20px;">visibility</span>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--btxt);">Detail Observasi BOS'Q #{{ $temuan->id }}</div>
                    <div style="font-size:12px;color:var(--btxt2);">Dicatat {{ $temuan->created_at->diffForHumans() }}</div>
                </div>
            </div>
            {{-- Status Badge --}}
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
            @endphp
            <span class="sbadge {{ $statusClass }}" style="font-size:12px;padding:5px 14px;">{{ $statusText }}</span>
        </div>

        <div class="bcard-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="info-row">
                        <div class="inf-label">Tanggal Observasi</div>
                        <div class="inf-value">{{ $temuan->tanggal_temuan->format('d F Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Departemen</div>
                        <div class="inf-value">{{ $temuan->departemen->nama_departemen ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Line / Sub Area</div>
                        <div class="inf-value">
                            {{ $temuan->line->nama_line ?? '-' }} /
                            {{ $temuan->subArea->nama_sub_area ?? '-' }}
                            @if($temuan->detail_sub_area)
                                <span style="font-size:12px;color:var(--btxt2);">({{ $temuan->detail_sub_area }})</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Elemen QFS</div>
                        <div class="inf-value">
                            <span style="font-size:11px;font-weight:700;background:var(--bp-light);color:var(--bp-dark);padding:2px 8px;border-radius:6px;margin-right:6px;">BOS'Q</span>
                            {{ $temuan->elemenQfs->nama_elemen ?? '-' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Observer (Pelapor)</div>
                        <div class="inf-value">
                            {{ $temuan->pelapor->name ?? '-' }} ({{ $temuan->pelapor->nik ?? '-' }})
                            @if($isPelapor)
                                <span style="padding:2px 8px;font-size:10px;font-weight:700;background:#e8f5e9;color:#2e7d32;border-radius:6px;border:1px solid rgba(46,125,50,0.2);">Anda</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    <div class="info-row">
                        <div class="inf-label">Auditee (yang Diobservasi)</div>
                        <div class="inf-value">
                            {{ $temuan->auditee->name ?? '-' }} ({{ $temuan->auditee->nik ?? '-' }})
                            @if($isAuditee)
                                <span style="padding:2px 8px;font-size:10px;font-weight:700;background:#e3f2fd;color:#1565c0;border-radius:6px;border:1px solid rgba(21,101,192,0.2);">Anda</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Tingkat Risiko</div>
                        <div class="inf-value">
                            @php
                                $risikoLabel = match($temuan->tingkat_resiko) {
                                    'food_safety_risk'   => 'Food Safety Risk',
                                    'major_quality_risk' => 'Major Quality Risk',
                                    'minor_quality_risk' => 'Minor Quality Risk',
                                    default              => $temuan->tingkat_resiko,
                                };
                                $risikoColor = match($temuan->tingkat_resiko) {
                                    'food_safety_risk'   => ['#c62828', '#ffebee', '#ef9a9a'],
                                    'major_quality_risk' => ['#e65100', '#fff3e0', '#ffcc80'],
                                    default              => ['#1565c0', '#e3f2fd', '#90caf9'],
                                };
                            @endphp
                            <span style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:8px;background:{{ $risikoColor[1] }};color:{{ $risikoColor[0] }};border:1px solid {{ $risikoColor[2] }};">
                                {{ $risikoLabel }}
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Dampak Observasi</div>
                        <div class="inf-value">
                            @if($temuan->dampak_temuan === 'positif')
                                <span style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:8px;background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;">✓ Positif (Perilaku Baik)</span>
                            @else
                                <span style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:8px;background:#ffebee;color:#c62828;border:1px solid #ef9a9a;">⚠ Negatif (Butuh Tindak Lanjut)</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row" style="margin-bottom:0;">
                        <div class="inf-label">Tanggal Dibuat</div>
                        <div class="inf-value">{{ $temuan->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            {{-- Temuan BQA --}}
            <div style="margin-top:20px;padding-top:18px;border-top:1px solid var(--bbor);">
                <div class="inf-label" style="margin-bottom:8px;">Temuan Behavior Quality Audit (Terenkripsi)</div>
                <div style="background:var(--bsur);border:1px solid var(--bbor);border-radius:10px;padding:14px 16px;font-size:13.5px;line-height:1.65;color:var(--btxt);">
                    {{ $temuan->temuan_bqa }}
                </div>
            </div>
        </div>
    </div>

    {{-- Verification Summary Info --}}
    @if($temuan->tindakLanjut && $temuan->status === 'closed_acc')
        <div class="bcard fu2" style="margin-bottom:20px;">
            <div class="bcard-header">
                <div class="bcard-hicon" style="background:#e8f5e9;">
                    <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:20px;">task_alt</span>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--btxt);">Verifikasi Tim QA</div>
                    <div style="font-size:12px;color:var(--btxt2);">Observasi telah diverifikasi dan disetujui oleh QA</div>
                </div>
            </div>
            <div class="bcard-body">
                @php $tl = $temuan->tindakLanjut; @endphp
                @if($tl->catatan_qa)
                    <div class="info-row">
                        <div class="inf-label">Catatan QA</div>
                        <div class="inf-value" style="color:#2e7d32;">{{ $tl->catatan_qa }}</div>
                    </div>
                @endif
                @if($tl->tanggal_acc)
                    <div class="info-row" style="margin-bottom:0;">
                        <div class="inf-label">Tanggal Disetujui QA</div>
                        <div class="inf-value" style="color:#2e7d32;font-weight:600;">{{ $tl->tanggal_acc->format('d F Y, H:i') }} WIB</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- QA Verifikasi Panel --}}
    @if($showVerifikasiForm)
        <livewire:bos-q.verifikasi-q-a :temuan="$temuan" :key="'vqa-'.$temuan->id" />
    @endif

    <style>
        @media (max-width: 640px) {
            div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
        }
    </style>
</div>
