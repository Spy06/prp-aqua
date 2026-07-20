<div style="max-width:900px;margin:0 auto;" id="detail-temuan-container">

    {{-- Breadcrumb --}}
    <div class="breadcrumb fu">
        <a href="{{ route('beranda') }}">Beranda</a>
        <span class="material-symbols-outlined sep" style="font-size:16px;">chevron_right</span>
        <span style="color:var(--btxt);font-weight:600;">Temuan #{{ $temuan->id }}</span>
    </div>

    {{-- Card: Info Temuan --}}
    <div class="bcard fu1" style="margin-bottom:20px;">
        <div class="bcard-header" style="justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="bcard-hicon" style="background:#e3f2fd;">
                    <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:20px;">description</span>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--btxt);">Detail Temuan PRP #{{ $temuan->id }}</div>
                    <div style="font-size:12px;color:var(--btxt2);">Dilaporkan {{ $temuan->created_at->diffForHumans() }}</div>
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
                        <div class="inf-label">Tanggal Temuan</div>
                        <div class="inf-value">{{ $temuan->tanggal_temuan->format('d F Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Departemen</div>
                        <div class="inf-value">{{ $temuan->departemen->nama_departemen ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Sub Area</div>
                        <div class="inf-value">{{ $temuan->sub_area }}</div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Klausul PRP</div>
                        <div class="inf-value" style="font-size:13px;">
                            {{ $temuan->klausul ? $temuan->klausul->kode_klausul . ' — ' . $temuan->klausul->nama_klausul : '-' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Pelapor</div>
                        <div class="inf-value">{{ $temuan->pelapor->name ?? '-' }}</div>
                    </div>
                    <div class="info-row" style="margin-bottom:0;">
                        <div class="inf-label">PIC yang Ditunjuk</div>
                        <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                            <div style="width:28px;height:28px;border-radius:50%;background:var(--bp);color:#fff;font-weight:700;font-size:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                {{ substr($temuan->pic->name ?? '?', 0, 1) }}
                            </div>
                            <div class="inf-value">{{ $temuan->pic->name ?? '-' }}</div>
                            @if($isPic)
                                <span style="padding:2px 8px;font-size:10px;font-weight:700;background:#e3f2fd;color:#1565c0;border-radius:6px;border:1px solid rgba(25,118,210,0.2);">Anda</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    <div class="info-row">
                        <div class="inf-label">Deskripsi Temuan</div>
                        <div class="inf-text">{{ $temuan->deskripsi }}</div>
                    </div>

                    @if($temuan->saran)
                    <div class="info-row">
                        <div class="inf-label">Saran & Masukan</div>
                        <div class="inf-text" style="background:#fff8e1;border-color:#ffe082;">{{ $temuan->saran }}</div>
                    </div>
                    @endif

                    @if($temuan->foto_temuan_path)
                    <div class="info-row" style="margin-bottom:0;">
                        <div class="inf-label">Foto Temuan</div>
                        <div style="border-radius:10px;overflow:hidden;border:1px solid var(--bbor);margin-top:6px;">
                            <img src="{{ asset('storage/' . $temuan->foto_temuan_path) }}"
                                 alt="Foto temuan PRP"
                                 style="width:100%;max-height:260px;object-fit:contain;background:var(--bsur);" />
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Tindak Lanjut (info ringkas) --}}
    @php $tl = $temuan->tindakLanjut; @endphp
    @if($tl)
    <div class="bcard fu2" style="margin-bottom:20px;">
        <div class="bcard-header">
            <div class="bcard-hicon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:20px;">task_alt</span>
            </div>
            <div style="font-size:15px;font-weight:700;color:var(--btxt);">Tindak Lanjut</div>
        </div>
        <div class="bcard-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                <div>
                    <div class="info-row">
                        <div class="inf-label">Tindakan Perbaikan</div>
                        @if($tl->action)
                            <div class="inf-text">{{ $tl->action }}</div>
                        @else
                            <div style="font-size:13px;color:var(--btxt2);font-style:italic;">Belum diisi</div>
                        @endif
                    </div>
                    <div class="info-row" style="margin-bottom:0;">
                        <div class="inf-label">Due Date</div>
                        @if($tl->due_date)
                            @php
                                $isOverdue = $tl->due_date->lt(now()) && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);
                            @endphp
                            <div class="inf-value" style="{{ $isOverdue ? 'color:var(--error);' : '' }}">
                                {{ $tl->due_date->format('d F Y') }}
                                @if($isOverdue)
                                    <span style="font-size:10px;font-weight:700;color:var(--error);border:1px solid currentColor;padding:1px 6px;border-radius:4px;margin-left:6px;">Overdue</span>
                                @endif
                            </div>
                        @else
                            <div style="font-size:13px;color:var(--btxt2);font-style:italic;">Belum diisi</div>
                        @endif
                    </div>
                </div>

                <div>
                    @if($tl->foto_bukti_path)
                    <div class="info-row">
                        <div class="inf-label">Foto Bukti</div>
                        <div style="border-radius:10px;overflow:hidden;border:1px solid var(--bbor);margin-top:6px;">
                            <img src="{{ asset('storage/' . $tl->foto_bukti_path) }}"
                                 alt="Foto bukti tindak lanjut"
                                 style="width:100%;max-height:180px;object-fit:contain;background:var(--bsur);" />
                        </div>
                    </div>
                    @else
                    <div class="balert balert-warn">
                        <span class="material-symbols-outlined" style="font-size:18px;flex-shrink:0;">warning</span>
                        Foto bukti belum diupload
                    </div>
                    @endif

                    @if($tl->catatan_qa)
                    <div class="info-row" style="margin-top:14px;margin-bottom:0;">
                        <div class="inf-label">Catatan QA</div>
                        <div class="inf-text" style="background:#f3e5f5;border-color:#e1bee7;">{{ $tl->catatan_qa }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Panel: Form TindakLanjutPIC --}}
    @if($showTindakLanjutForm)
    <div class="bcard fu3" style="margin-bottom:20px;">
        <div class="bcard-header">
            <div class="bcard-hicon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:20px;">edit_document</span>
            </div>
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--btxt);">Form Tindak Lanjut PIC</div>
                <div style="font-size:12px;color:var(--btxt2);">Isi detail tindakan perbaikan dan update status</div>
            </div>
        </div>
        <div class="bcard-body">
            <livewire:tindak-lanjut-p-i-c :temuanId="$temuan->id" :key="'tl-' . $temuan->id" />
        </div>
    </div>
    @endif

    {{-- Info: Pelapor (read-only) --}}
    @if($isPelapor && !$isPic)
    <div class="balert balert-info fu3">
        <span class="material-symbols-outlined" style="font-size:20px;flex-shrink:0;">info</span>
        <span>Anda adalah pelapor temuan ini. Tindak lanjut dilakukan oleh PIC yang ditunjuk.</span>
    </div>
    @endif

    {{-- Panel: Verifikasi QA --}}
    @if($isQa && $temuan->status === 'closed_pending_qa')
        <livewire:verifikasi-q-a :temuan="$temuan" :key="'vqa-' . $temuan->id" />
    @elseif($isQa)
    <div class="balert balert-warn fu3" style="margin-top:16px;">
        <span class="material-symbols-outlined" style="font-size:20px;flex-shrink:0;">shield</span>
        <span>Verifikasi QA hanya bisa dilakukan saat status temuan adalah <strong>Pending QA</strong>.</span>
    </div>
    @endif

    <style>
        .info-row { margin-bottom: 16px; }
        @media (max-width: 640px) {
            div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
        }
    </style>
</div>
