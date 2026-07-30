<div style="max-width:900px;margin:0 auto;" id="bosq-detail-container" x-data="{ lightboxOpen: false, lightboxSrc: '', lightboxTitle: '' }">

    {{-- Breadcrumb --}}
    <div class="breadcrumb fu">
        <a href="{{ route('bosq.beranda') }}">BOS'Q Beranda</a>
        <span class="material-symbols-outlined sep" style="font-size:16px;">chevron_right</span>
        <span style="color:var(--btxt);font-weight:600;">Observasi #{{ $temuan->id }}</span>
    </div>

    {{-- Flash Notifications --}}
    @if(session()->has('success'))
        <div class="fu" style="padding:14px 18px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:12px;color:#2e7d32;font-weight:600;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="fu" style="padding:14px 18px;background:#ffebee;border:1px solid #ef9a9a;border-radius:12px;color:#c62828;font-weight:600;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session()->has('info'))
        <div class="fu" style="padding:14px 18px;background:#e3f2fd;border:1px solid #90caf9;border-radius:12px;color:#1565c0;font-weight:600;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">info</span>
            <span>{{ session('info') }}</span>
        </div>
    @endif

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
                $isClosed    = in_array($temuan->status, ['closed', 'closed_acc']);
                $statusClass = $isClosed ? 'sbadge-closed' : 'sbadge-open';
                $statusText  = $isClosed ? 'Closed' : 'Open';
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
                        <div class="inf-label">Sub Area</div>
                        <div class="inf-value">
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
                            {{ $temuan->pelapor->name ?? '-' }} ({{ $temuan->pelapor->karyawan->departemen->nama_departemen ?? 'Tanpa Departemen' }})
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
                            {{ $temuan->auditee->name ?? '-' }} ({{ $temuan->auditee->karyawan->departemen->nama_departemen ?? 'Tanpa Departemen' }})
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

                @if($temuan->tindakLanjut && $temuan->tindakLanjut->action)
                <div style="margin-top:16px;padding-top:16px;border-top:1px dashed var(--bbor);">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;flex-wrap:wrap;gap:6px;">
                        <div class="inf-label" style="margin:0;">Action (Jika Negatif)</div>
                        @if($temuan->tindakLanjut->due_date)
                            <div style="font-size:12px;font-weight:600;color:#e65100;background:#fff3e0;padding:3px 10px;border-radius:6px;border:1px solid #ffe0b2;display:flex;align-items:center;gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px;">calendar_today</span>
                                <span>Due Date: {{ \Carbon\Carbon::parse($temuan->tindakLanjut->due_date)->format('d F Y') }}</span>
                            </div>
                        @endif
                    </div>
                    <div style="background:#fff3e0;border:1px solid #ffe0b2;border-radius:10px;padding:12px 14px;font-size:13.5px;line-height:1.6;color:#e65100;font-weight:500;">
                        {{ $temuan->tindakLanjut->action }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Card: Action Ubah Status ke CLOSED oleh Observer / Pelapor --}}
    @if($isPelapor || $isQa)
        <div class="bcard fu2" style="margin-bottom:20px;border:1.5px solid {{ $isClosed ? '#a5d6a7' : '#90caf9' }};background:{{ $isClosed ? '#f1f8e9' : '#f4f8fb' }};">
            <div class="bcard-header" style="justify-content:space-between;border-bottom:1px solid {{ $isClosed ? '#c8e6c9' : '#e0e0e0' }};">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="bcard-hicon" style="background:{{ $isClosed ? '#e8f5e9' : '#e3f2fd' }};">
                        <span class="material-symbols-outlined fil" style="color:{{ $isClosed ? '#2e7d32' : '#1565c0' }};font-size:20px;">
                            {{ $isClosed ? 'task_alt' : 'published_with_changes' }}
                        </span>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--btxt);">
                            Status Perbaikan Observasi
                        </div>
                        <div style="font-size:12px;color:var(--btxt2);">
                            {{ $isClosed ? 'Observasi ini telah ditandai selesai (CLOSED).' : 'Jika perbaikan telah Anda lakukan, ubah status observasi menjadi Closed.' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="bcard-body" style="padding:20px;">
                @if(!$isClosed)
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                        <div>
                            <div style="font-size:13.5px;font-weight:600;color:var(--btxt);">Status saat ini: <span style="color:#e65100;font-weight:700;">OPEN</span></div>
                            <div style="font-size:12px;color:var(--btxt2);margin-top:2px;">Tekan tombol di samping jika perbaikan observasi sudah selesai dilakukan.</div>
                        </div>

                        <button type="button" wire:click="ubahStatusClosed" wire:confirm="Apakah Anda yakin ingin mengubah status observasi ini menjadi CLOSED?"
                            class="bbtn bbtn-primary" style="background:#2e7d32;border-color:#2e7d32;box-shadow:0 4px 14px rgba(46,125,50,0.3);padding:10px 20px;font-weight:700;">
                            <span class="material-symbols-outlined fil" style="font-size:18px;">check_circle</span>
                            Tandai Selesai (Ubah Status ke CLOSED)
                        </button>
                    </div>
                @else
                    <div style="display:flex;align-items:center;gap:10px;color:#2e7d32;font-weight:600;font-size:14px;">
                        <span class="material-symbols-outlined fil" style="font-size:22px;">check_circle</span>
                        <span>Observasi ini sudah Selesai (CLOSED). Laporan disimpan sebagai arsip observasi Anda.</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <style>
        @media (max-width: 640px) {
            div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
        }
    </style>
</div>
