<div style="max-width:900px;margin:0 auto;" id="detail-temuan-container" x-data="{ lightboxOpen: false, lightboxSrc: '', lightboxTitle: '' }">

    {{-- Breadcrumb --}}
    <div class="breadcrumb fu">
        <a href="{{ route('beranda') }}">Beranda</a>
        <span class="material-symbols-outlined sep" style="font-size:16px;">chevron_right</span>
        <span style="color:var(--btxt);font-weight:600;">Temuan #{{ $temuan->id }}</span>
    </div>

    {{-- Card: Info Temuan --}}
    <div class="bcard fu1" style="margin-bottom:20px;">
        <div class="bcard-header">
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
                        <div class="inf-label">Departemen & Area</div>
                        <div class="inf-value">{{ $temuan->departemen->nama_departemen ?? '-' }}</div>
                        <div style="font-size:12px;color:var(--bp);margin-top:2px;font-weight:600;">
                            Sub Area: {{ $temuan->sub_area }}{{ $temuan->detail_sub_area ? ' ('.$temuan->detail_sub_area.')' : '' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Klausul PRP</div>
                        <div class="inf-value">
                            @if($temuan->klausul)
                                <span style="font-size:11px;font-weight:700;background:var(--bp-light);color:var(--bp-dark);padding:2px 8px;border-radius:6px;margin-right:6px;">
                                    {{ $temuan->klausul->kode_klausul }}
                                </span>
                                {{ $temuan->klausul->nama_klausul }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="inf-label">Pelapor</div>
                        <div class="inf-value">
                            {{ $temuan->pelapor->name ?? '-' }} ({{ $temuan->pelapor->karyawan->departemen->nama_departemen ?? 'Tanpa Departemen' }})
                            @if($isPelapor)
                                <span style="padding:2px 8px;font-size:10px;font-weight:700;background:#e8f5e9;color:#2e7d32;border-radius:6px;border:1px solid rgba(46,125,50,0.2);">Anda</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row" style="margin-bottom:0;">
                        <div class="inf-label">PIC Penanggung Jawab</div>
                        <div class="inf-value">
                            {{ $temuan->pic->name ?? '-' }} ({{ $temuan->pic->karyawan->departemen->nama_departemen ?? 'Tanpa Departemen' }})
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
                        <div style="border-radius:10px;overflow:hidden;border:1px solid var(--bbor);margin-top:6px;position:relative;cursor:pointer;background:var(--bsur);"
                             @click="lightboxOpen = true; lightboxSrc = '{{ asset('storage/' . $temuan->foto_temuan_path) }}'; lightboxTitle = 'Foto Temuan PRP #{{ $temuan->id }}'"
                             class="img-hover-container"
                             title="Klik untuk memperbesar gambar">
                            <img src="{{ asset('storage/' . $temuan->foto_temuan_path) }}"
                                 alt="Foto temuan PRP"
                                 style="width:100%;max-height:260px;object-fit:contain;display:block;transition:transform 0.25s;" />
                            <div class="img-hover-overlay">
                                <span class="material-symbols-outlined" style="font-size:26px;color:#fff;">zoom_in</span>
                                <span style="font-size:12px;color:#fff;font-weight:600;">Klik untuk Perbesar</span>
                            </div>
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
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="bcard-hicon" style="background:#e8f5e9;">
                    <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:20px;">task_alt</span>
                </div>
                <div style="font-size:15px;font-weight:700;color:var(--btxt);">Tindak Lanjut</div>
            </div>
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
                    @php
                        $buktiPaths = $tl->bukti_paths ?? [];
                    @endphp
                    @if(count($buktiPaths) > 0)
                    <div class="info-row">
                        <div class="inf-label">File / Foto Bukti Tindak Lanjut ({{ count($buktiPaths) }} File)</div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));gap:10px;margin-top:6px;">
                            @foreach($buktiPaths as $index => $path)
                                @php
                                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp
                                @if($isImage)
                                    <div style="border-radius:10px;overflow:hidden;border:1px solid var(--bbor);background:var(--bsur);display:flex;flex-direction:column;position:relative;"
                                         class="img-hover-container">
                                        <div style="height:140px;overflow:hidden;position:relative;cursor:pointer;"
                                             @click="lightboxOpen = true; lightboxSrc = '{{ asset('storage/' . $path) }}'; lightboxTitle = 'Foto Bukti Tindak Lanjut #{{ $index + 1 }} (Temuan #{{ $temuan->id }})'"
                                             title="Klik untuk memperbesar gambar">
                                            <img src="{{ asset('storage/' . $path) }}"
                                                 alt="Foto bukti tindak lanjut"
                                                 style="width:100%;height:140px;object-fit:cover;display:block;transition:transform 0.25s;" />
                                            <div class="img-hover-overlay">
                                                <span class="material-symbols-outlined" style="font-size:24px;color:#fff;">zoom_in</span>
                                                <span style="font-size:11px;color:#fff;font-weight:600;">Klik Perbesar</span>
                                            </div>
                                        </div>
                                        <div style="padding:6px 10px;background:var(--bcard);border-top:1px solid var(--bbor);display:flex;justify-content:space-between;align-items:center;">
                                            <span style="font-size:11px;color:var(--btxt2);font-weight:600;">Foto #{{ $index + 1 }}</span>
                                            <a href="{{ asset('storage/' . $path) }}" target="_blank" download style="font-size:11px;color:var(--bp);text-decoration:none;font-weight:600;display:flex;align-items:center;gap:2px;">
                                                <span class="material-symbols-outlined" style="font-size:14px;">download</span> Unduh
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div style="display:flex;flex-direction:column;justify-content:space-between;padding:10px 12px;background:var(--bsur);border:1px solid var(--bbor);border-radius:10px;">
                                        <div style="display:flex;align-items:center;gap:8px;overflow:hidden;margin-bottom:8px;">
                                            <span class="material-symbols-outlined" style="font-size:26px;color:{{ $ext === 'pdf' ? '#c62828' : '#1565c0' }};flex-shrink:0;">
                                                {{ $ext === 'pdf' ? 'picture_as_pdf' : 'description' }}
                                            </span>
                                            <div style="overflow:hidden;">
                                                <div style="font-size:12px;font-weight:600;color:var(--btxt);" class="truncate">Dokumen #{{ $index + 1 }} (.{{ strtoupper($ext) }})</div>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $path) }}" target="_blank" download class="bbtn bbtn-secondary bbtn-sm" style="width:100%;justify-content:center;font-size:11px !important;">
                                            <span class="material-symbols-outlined" style="font-size:14px;">download</span>
                                            Buka / Unduh
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="balert balert-warn">
                        <span class="material-symbols-outlined" style="font-size:18px;flex-shrink:0;">warning</span>
                        File / Foto bukti belum diupload
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
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="bcard-hicon" style="background:#e3f2fd;">
                    <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:20px;">edit_document</span>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--btxt);">Form Tindak Lanjut PIC</div>
                    <div style="font-size:12px;color:var(--btxt2);">Isi detail tindakan perbaikan dan update status</div>
                </div>
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

    {{-- ═══ LIGHTBOX MODAL PREVIEW HIGH-RES ═══ --}}
    <template x-teleport="body">
        <div x-show="lightboxOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @keydown.escape.window="lightboxOpen = false"
             style="position:fixed!important;top:0!important;left:0!important;right:0!important;bottom:0!important;width:100vw!important;height:100vh!important;margin:0!important;z-index:999999!important;display:flex!important;flex-direction:column!important;align-items:center!important;justify-content:center!important;padding:20px;background:rgba(15,23,42,0.92);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);box-sizing:border-box;"
             x-cloak>
            
            {{-- Lightbox Topbar --}}
            <div style="width:100%;max-width:min(960px, 92vw);display:flex;align-items:center;justify-content:space-between;margin:0 auto 14px;color:#fff;background:rgba(30,41,59,0.85);padding:10px 16px;border-radius:12px;border:1px solid rgba(255,255,255,0.12);box-shadow:0 4px 20px rgba(0,0,0,0.4);flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:8px;overflow:hidden;">
                    <span class="material-symbols-outlined" style="color:var(--bp);font-size:22px;">zoom_in</span>
                    <h4 style="margin:0;font-size:14px;font-weight:700;letter-spacing:-0.2px;color:#fff;" class="truncate" x-text="lightboxTitle"></h4>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                    <a :href="lightboxSrc" target="_blank" download class="bbtn bbtn-secondary bbtn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border:none!important;padding:6px 12px;">
                        <span class="material-symbols-outlined" style="font-size:16px;">download</span> Unduh Original
                    </a>
                    <button @click="lightboxOpen = false" style="background:rgba(255,255,255,0.2);color:#fff;border:none;width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.2s;" title="Tutup (Esc)">
                        <span class="material-symbols-outlined" style="font-size:20px;">close</span>
                    </button>
                </div>
            </div>

            {{-- Image Container (Seamless, no black side boxes) --}}
            <div style="display:flex;align-items:center;justify-content:center;width:100%;max-width:min(960px, 92vw);margin:0 auto;" @click.outside="lightboxOpen = false">
                <img :src="lightboxSrc" :alt="lightboxTitle" style="max-width:100%;max-height:76vh;object-fit:contain;display:block;border-radius:12px;box-shadow:0 25px 60px rgba(0,0,0,0.7);user-select:none;">
            </div>

            <div style="margin-top:12px;color:rgba(255,255,255,0.7);font-size:12px;text-align:center;">
                Tekan <kbd style="background:rgba(255,255,255,0.2);padding:2px 6px;border-radius:4px;color:#fff;font-weight:600;">Esc</kbd> atau klik di luar gambar untuk menutup
            </div>
        </div>
    </template>

    <style>
        .info-row { margin-bottom: 16px; }
        
        /* Image Hover Overlay Styles */
        .img-hover-container {
            position: relative !important;
            overflow: hidden !important;
        }
        .img-hover-overlay {
            position: absolute !important;
            inset: 0 !important;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
        }
        .img-hover-container:hover .img-hover-overlay {
            opacity: 1;
        }
        .img-hover-container:hover img {
            transform: scale(1.03);
        }

        @media (max-width: 640px) {
            div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
        }
    </style>
</div>
