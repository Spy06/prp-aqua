<div x-data="{ picLightboxOpen: false, picLightboxSrc: '', picLightboxTitle: '', showConfirmPendingQa: false }">
    {{-- Flash Notifications --}}
    @if (session()->has('status_success'))
        <div class="balert balert-success fu" style="margin-bottom:16px;">
            <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
            <span>{{ session('status_success') }}</span>
        </div>
    @endif

    @if (session()->has('status_error'))
        <div class="balert balert-error fu" style="margin-bottom:16px;">
            <span class="material-symbols-outlined" style="font-size:18px;">error</span>
            <span>{{ session('status_error') }}</span>
        </div>
    @endif

    @if (session()->has('detail_success'))
        <div class="balert balert-success fu" style="margin-bottom:16px;">
            <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
            <span>{{ session('detail_success') }}</span>
        </div>
    @endif

    @if (session()->has('foto_success'))
        <div class="balert balert-success fu" style="margin-bottom:16px;">
            <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
            <span>{{ session('foto_success') }}</span>
        </div>
    @endif

    @if (session()->has('foto_error'))
        <div class="balert balert-error fu" style="margin-bottom:16px;">
            <span class="material-symbols-outlined" style="font-size:18px;">error</span>
            <span>{{ session('foto_error') }}</span>
        </div>
    @endif

    {{-- Header Banner Status --}}
    <div style="background:var(--bsur);padding:14px 16px;border-radius:12px;border:1px solid var(--bbor);margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <span style="font-size:11px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:0.5px;display:block;">Status Tindak Lanjut</span>
            <span style="font-size:15px;font-weight:700;color:var(--btxt);margin-top:2px;display:inline-flex;align-items:center;gap:6px;">
                @if($currentStatus === 'open')
                    <span class="sbadge sbadge-open">Open</span>
                @elseif($currentStatus === 'in_progress')
                    <span class="sbadge sbadge-progress">In Progress</span>
                @elseif($currentStatus === 'closed_pending_qa')
                    <span class="sbadge sbadge-pending">Closed (Pending QA)</span>
                @elseif($currentStatus === 'closed_acc')
                    <span class="sbadge sbadge-closed">Closed ACC (Selesai)</span>
                @endif
            </span>
        </div>
        @if($currentStatus === 'closed_acc')
            <span style="font-size:12px;color:#2e7d32;font-weight:600;background:#e8f5e9;padding:6px 12px;border-radius:8px;">
                ✓ Laporan sudah diverifikasi ACC oleh QA
            </span>
        @elseif($currentStatus === 'closed_pending_qa')
            <span style="font-size:12px;color:#c62828;font-weight:600;background:#ffebee;padding:6px 12px;border-radius:8px;">
                ⏳ Menunggu verifikasi akhir QA
            </span>
        @endif
    </div>

    {{-- Section 1: Form Tindakan & Due Date --}}
    <form wire:submit.prevent="simpanDetail" style="margin-bottom:20px;">
        <div style="display:flex;flex-direction:column;gap:14px;">

            {{-- Deskripsi Tindakan --}}
            <div>
                <label for="action" class="blabel">Rencana / Deskripsi Tindakan Perbaikan <span style="color:var(--error);">*</span></label>
                @if($currentStatus !== 'closed_acc')
                    <textarea id="action" wire:model="action" class="binput" rows="4"
                        placeholder="Jelaskan tindakan perbaikan yang dilakukan atau akan dilakukan..."></textarea>
                    @error('action') <span class="berr">{{ $message }}</span> @enderror
                @else
                    <div class="inf-text">{{ $action ?: '-' }}</div>
                @endif
            </div>

            {{-- Due Date --}}
            <div>
                <label for="due_date" class="blabel">Target Selesai (Due Date) <span style="color:var(--error);">*</span></label>
                @if($currentStatus !== 'closed_acc')
                    <input type="date" id="due_date" wire:model="due_date" class="binput" style="max-width:240px;" />
                    @error('due_date') <span class="berr">{{ $message }}</span> @enderror
                @else
                    <div class="inf-value" style="margin-top:4px;">
                        {{ $due_date ? \Carbon\Carbon::parse($due_date)->format('d F Y') : '-' }}
                    </div>
                @endif
            </div>

            {{-- Tombol Simpan Detail (hanya tampil jika belum closed_acc) --}}
            @if($currentStatus !== 'closed_acc')
            <div>
                <button type="submit" class="bbtn bbtn-secondary bbtn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    Simpan Detail
                </button>
            </div>
            @endif
        </div>
    </form>

    {{-- Section 2: File / Foto Bukti (Maksimal 3 File) --}}
    <div style="padding-top:16px;border-top:1.5px solid var(--bbor);margin-bottom:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="color:var(--bp);font-size:18px;">description</span>
                <span style="font-size:13px;font-weight:700;color:var(--btxt);text-transform:uppercase;letter-spacing:0.5px;">File / Foto Bukti</span>
                <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;{{ count($buktiPaths) > 0 ? 'background:var(--bp-light);color:var(--bp-dark);' : 'background:#fff3e0;color:#e65100;' }}">
                    {{ count($buktiPaths) }}/3 File
                </span>
            </div>

            @if(count($buktiPaths) === 0)
                <span style="font-size:11.5px;color:#e65100;font-weight:600;">(Wajib minimal 1 file sebelum closed)</span>
            @else
                <span style="font-size:11.5px;color:#2e7d32;font-weight:600;">✓ Bukti diupload</span>
            @endif
        </div>

        {{-- Grid Bukti Ter-upload --}}
        @if(count($buktiPaths) > 0)
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));gap:12px;margin-bottom:14px;">
                @foreach($buktiPaths as $index => $path)
                    @php
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                    @endphp
                    <div style="position:relative;background:var(--bcard);border:1.5px solid var(--bbor);border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.04);display:flex;flex-direction:column;"
                         class="pic-img-hover-container">
                        @if($isImage)
                            <div style="height:120px;background:var(--bsur);display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;cursor:pointer;"
                                 @click="picLightboxOpen = true; picLightboxSrc = '{{ Storage::disk('public')->url($path) }}'; picLightboxTitle = 'Foto Bukti #{{ $index + 1 }}'"
                                 title="Klik untuk memperbesar gambar">
                                <img src="{{ Storage::disk('public')->url($path) }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.25s;">
                                <div class="pic-img-hover-overlay">
                                    <span class="material-symbols-outlined" style="font-size:22px;color:#fff;">zoom_in</span>
                                    <span style="font-size:10.5px;color:#fff;font-weight:600;">Perbesar</span>
                                </div>
                            </div>
                        @else
                            <div style="height:120px;background:var(--bsur);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:12px;gap:6px;text-align:center;">
                                <span class="material-symbols-outlined" style="font-size:40px;color:{{ $ext === 'pdf' ? '#c62828' : '#1565c0' }};">
                                    {{ $ext === 'pdf' ? 'picture_as_pdf' : 'description' }}
                                </span>
                                <span style="font-size:11px;font-weight:700;color:var(--btxt);text-transform:uppercase;">.{{ strtoupper($ext) }} File</span>
                            </div>
                        @endif

                        <div style="padding:8px 10px;display:flex;align-items:center;justify-content:space-between;background:var(--bcard);border-top:1px solid var(--bbor);gap:6px;">
                            <a href="{{ Storage::disk('public')->url($path) }}" target="_blank" download style="font-size:11.5px;font-weight:600;color:var(--bp);text-decoration:none;display:flex;align-items:center;gap:4px;overflow:hidden;">
                                <span class="material-symbols-outlined" style="font-size:15px;">download</span>
                                <span class="truncate">File #{{ $index + 1 }}</span>
                            </a>

                            @if($currentStatus !== 'closed_acc')
                                <button type="button" wire:click="hapusFotoBukti({{ $index }})" wire:confirm="Hapus file bukti ini?" style="background:var(--error-light);color:var(--error);border:none;width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;" title="Hapus file">
                                    <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Dropzone Input File --}}
        @if($currentStatus !== 'closed_acc')
            @if(count($buktiPaths) < 3)
                <div>
                    <div style="border:2px dashed var(--bbor);border-radius:12px;padding:18px;display:flex;flex-direction:column;align-items:center;justify-content:center;background:var(--bsur);min-height:120px;gap:10px;">
                        <input type="file" id="foto-bukti-gallery" wire:model="foto_bukti" onchange="handleFotoFileSelect(event)" multiple accept="image/*,.pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" style="display:none;" />
                        <input type="file" id="foto-bukti-camera" wire:model="foto_bukti" onchange="handleFotoFileSelect(event)" accept="image/*" capture="environment" style="display:none;" />

                        <span class="material-symbols-outlined" style="font-size:32px;color:var(--btxt2);">cloud_upload</span>
                        <p style="font-size:12.5px;color:var(--btxt2);margin:0;text-align:center;">Tambah file bukti (Tersisa {{ 3 - count($buktiPaths) }} file lagi)</p>

                        <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                            <label for="foto-bukti-camera" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bp);color:#fff;font-size:12px;font-weight:600;border-radius:8px;transition:opacity .2s;">
                                <span class="material-symbols-outlined" style="font-size:16px;">photo_camera</span>
                                Ambil Foto
                            </label>
                            <label for="foto-bukti-gallery" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bsur);color:var(--btxt);font-size:12px;font-weight:600;border-radius:8px;border:1.5px solid var(--bbor);transition:opacity .2s;">
                                <span class="material-symbols-outlined" style="font-size:16px;">upload_file</span>
                                Pilih File / Galeri
                            </label>
                        </div>
                        <p style="font-size:11px;color:var(--btxt2);margin:4px 0 0 0;text-align:center;">Maksimal 3 file &bull; Maks 3MB per file &bull; Format: JPG, PNG, WEBP, PDF, Word</p>
                    </div>
                    @error('foto_bukti') <span class="berr" style="display:block;margin-top:6px;">{{ $message }}</span> @enderror
                    @error('foto_bukti.*') <span class="berr" style="display:block;margin-top:6px;">{{ $message }}</span> @enderror
                    <div wire:loading wire:target="foto_bukti" style="font-size:12px;color:var(--bp);margin-top:6px;display:flex;align-items:center;gap:6px;">
                        <span class="material-symbols-outlined" style="font-size:16px;animation:spin 1s linear infinite;">sync</span>
                        Mengunggah file...
                    </div>
                </div>
            @else
                <div style="padding:10px 14px;background:var(--bsur);border:1px solid var(--bbor);border-radius:10px;font-size:12px;color:var(--btxt2);text-align:center;">
                    ✓ Kuota maksimal 3 file bukti sudah terpenuhi. Hapus salah satu file di atas jika ingin mengganti.
                </div>
            @endif
        @endif
    </div>

    {{-- Section 3: Ubah Status --}}
    @if(!in_array($currentStatus, ['closed_acc']))
    <div style="padding-top:16px;border-top:1.5px solid var(--bbor);">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
            <span class="material-symbols-outlined" style="color:var(--bp);font-size:18px;">change_circle</span>
            <span style="font-size:13px;font-weight:700;color:var(--btxt);text-transform:uppercase;letter-spacing:0.5px;">Ubah Status</span>
        </div>

        {{-- Status Flow Indicator --}}
        <div style="display:flex;align-items:center;gap:3px;flex-wrap:nowrap;margin-bottom:14px;font-size:10.5px;">
            <span style="padding:4px 6px;border-radius:6px;white-space:nowrap;{{ $currentStatus === 'open' ? 'background:#fff3e0;color:#e65100;font-weight:700;' : 'background:var(--bsur);color:var(--btxt2);' }}">Open</span>
            <span class="material-symbols-outlined" style="font-size:12px;color:var(--btxt2);flex-shrink:0;">arrow_forward</span>
            <span style="padding:4px 6px;border-radius:6px;white-space:nowrap;{{ $currentStatus === 'in_progress' ? 'background:#e3f2fd;color:#1565c0;font-weight:700;' : 'background:var(--bsur);color:var(--btxt2);' }}">In Progress</span>
            <span class="material-symbols-outlined" style="font-size:12px;color:var(--btxt2);flex-shrink:0;">arrow_forward</span>
            <span style="padding:4px 6px;border-radius:6px;white-space:nowrap;{{ $currentStatus === 'closed_pending_qa' ? 'background:#ffebee;color:#c62828;font-weight:700;' : 'background:var(--bsur);color:var(--btxt2);' }}">Pending QA</span>
            <span class="material-symbols-outlined" style="font-size:12px;color:var(--btxt2);flex-shrink:0;">arrow_forward</span>
            <span style="padding:4px 6px;border-radius:6px;white-space:nowrap;{{ $currentStatus === 'closed_acc' ? 'background:#e8f5e9;color:#2e7d32;font-weight:700;' : 'background:var(--bsur);color:var(--btxt2);' }}">Closed ACC</span>
        </div>

        {{-- Tombol Aksi Status --}}
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            @if($currentStatus === 'open')
                <button type="button" wire:click="ubahStatus('in_progress')" class="bbtn bbtn-primary bbtn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;">play_arrow</span>
                    Proses (Mulai In Progress)
                </button>
            @endif

            @if($currentStatus === 'in_progress')
                <button type="button" @click="showConfirmPendingQa = true"
                    class="bbtn bbtn-success bbtn-sm"
                    {{ empty($buktiPaths) ? 'disabled' : '' }}>
                    <span class="material-symbols-outlined" style="font-size:16px;">task_alt</span>
                    Ajukan Selesai (Closed Pending QA)
                </button>

                @if(empty($buktiPaths))
                    <div style="font-size:11.5px;color:#e65100;margin-top:6px;width:100%;">
                        ⚠️ Upload minimal 1 file/foto bukti terlebih dahulu untuk mengaktifkan tombol ini.
                    </div>
                @endif
            @endif
        </div>
    </div>
    @endif

    {{-- Modal Dialog Konfirmasi Pengajuan Selesai --}}
    <template x-teleport="body">
        <div x-show="showConfirmPendingQa"
             x-cloak
             style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:999999;background:rgba(15,23,42,0.65);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.outside="showConfirmPendingQa = false"
                 style="position:absolute;top:0;bottom:0;left:0;right:0;margin:auto;height:fit-content;background:var(--bcard, #ffffff);border:1.5px solid var(--bbor, #e2e8f0);border-radius:18px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.35);max-width:440px;width:calc(100% - 40px);padding:28px 24px;text-align:center;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">

                <div style="width:60px;height:60px;border-radius:50%;background:#e8f5e9;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;box-shadow:0 4px 12px rgba(46,125,50,0.15);">
                    <span class="material-symbols-outlined fil" style="font-size:32px;color:#2e7d32;">task_alt</span>
                </div>

                <h3 style="font-size:18px;font-weight:700;color:var(--btxt, #1e293b);margin:0 0 8px 0;">Konfirmasi Pengajuan Selesai</h3>
                <p style="font-size:13.5px;color:var(--btxt2, #64748b);line-height:1.6;margin:0 0 24px 0;">
                    Apakah Anda yakin tindakan perbaikan dan bukti yang dilampirkan sudah lengkap dan ingin diajukan ke QA untuk verifikasi?
                </p>

                <div style="display:flex;gap:10px;justify-content:center;">
                    <button type="button"
                            @click="showConfirmPendingQa = false"
                            class="bbtn bbtn-secondary"
                            style="flex:1;justify-content:center;padding:10px 16px;border-radius:10px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                        Batal
                    </button>
                    <button type="button"
                            @click="showConfirmPendingQa = false; $wire.ubahStatus('closed_pending_qa');"
                            class="bbtn bbtn-success"
                            style="flex:1;justify-content:center;padding:10px 16px;border-radius:10px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">check</span>
                        Ya, Ajukan Selesai
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- Lightbox Modal untuk PIC --}}
    <template x-teleport="body">
        <div x-show="picLightboxOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @keydown.escape.window="picLightboxOpen = false"
             style="position:fixed!important;top:0!important;left:0!important;right:0!important;bottom:0!important;width:100vw!important;height:100vh!important;margin:0!important;z-index:999999!important;display:flex!important;flex-direction:column!important;align-items:center!important;justify-content:center!important;padding:20px;background:rgba(15,23,42,0.92);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);box-sizing:border-box;"
             x-cloak>
            
            <div style="width:100%;max-width:min(960px, 92vw);display:flex;align-items:center;justify-content:space-between;margin:0 auto 14px;color:#fff;background:rgba(30,41,59,0.85);padding:10px 16px;border-radius:12px;border:1px solid rgba(255,255,255,0.12);box-shadow:0 4px 20px rgba(0,0,0,0.4);flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:8px;overflow:hidden;">
                    <span class="material-symbols-outlined" style="color:var(--bp);font-size:22px;">zoom_in</span>
                    <h4 style="margin:0;font-size:14px;font-weight:700;color:#fff;" class="truncate" x-text="picLightboxTitle"></h4>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                    <a :href="picLightboxSrc" target="_blank" download class="bbtn bbtn-secondary bbtn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border:none!important;padding:6px 12px;">
                        <span class="material-symbols-outlined" style="font-size:16px;">download</span> Unduh Original
                    </a>
                    <button @click="picLightboxOpen = false" style="background:rgba(255,255,255,0.2);color:#fff;border:none;width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.2s;" title="Tutup (Esc)">
                        <span class="material-symbols-outlined" style="font-size:20px;">close</span>
                    </button>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:center;width:100%;max-width:min(960px, 92vw);margin:0 auto;" @click.outside="picLightboxOpen = false">
                <img :src="picLightboxSrc" :alt="picLightboxTitle" style="max-width:100%;max-height:76vh;object-fit:contain;display:block;border-radius:12px;box-shadow:0 25px 60px rgba(0,0,0,0.7);user-select:none;">
            </div>

            <div style="margin-top:12px;color:rgba(255,255,255,0.7);font-size:12px;text-align:center;">
                Tekan <kbd style="background:rgba(255,255,255,0.2);padding:2px 6px;border-radius:4px;color:#fff;font-weight:600;">Esc</kbd> atau klik di luar gambar untuk menutup
            </div>
        </div>
    </template>

    <style>
        .pic-img-hover-container { position: relative; }
        .pic-img-hover-overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,0.45); backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px;
            opacity: 0; transition: opacity 0.2s ease; pointer-events: none;
        }
        .pic-img-hover-container:hover .pic-img-hover-overlay { opacity: 1; }
        .pic-img-hover-container:hover img { transform: scale(1.04); }
    </style>

    <script>
    /**
     * SIVERA — Auto-compress gambar jika > 3MB sebelum diupload ke Livewire.
     * Mendukung multi-file upload di form PIC.
     */
    async function handleFotoFileSelect(event) {
        const input = event.target;
        if (!input.files || input.files.length === 0) return;
        
        const MAX_BYTES = 3 * 1024 * 1024; // 3 MB
        let needsCompression = false;
        
        // Cek apakah ada file gambar yang > 3MB
        for (let i = 0; i < input.files.length; i++) {
            let file = input.files[i];
            if (file.type.startsWith('image/') && file.size > MAX_BYTES) {
                needsCompression = true;
                break;
            }
        }

        // Jika semua file <= 3MB atau bukan gambar, biarkan Livewire memproses langsung
        if (!needsCompression) {
            return;
        }

        // Hentikan propagasi agar Livewire tidak mendeteksi file yang belum dikompres
        event.stopImmediatePropagation();
        
        const dt = new DataTransfer();

        for (let i = 0; i < input.files.length; i++) {
            let file = input.files[i];
            
            // Kompres hanya jika gambar dan > 3MB
            if (file.type.startsWith('image/') && file.size > MAX_BYTES) {
                try {
                    let compressedBlob = await compressImage(file);
                    let compressedFile = new File(
                        [compressedBlob],
                        file.name.replace(/\.[^.]+$/, '.jpg'),
                        { type: 'image/jpeg', lastModified: Date.now() }
                    );
                    dt.items.add(compressedFile);
                } catch (e) {
                    console.error('Kompresi gagal untuk file:', file.name, e);
                    dt.items.add(file); // Kembalikan file asli jika error
                }
            } else {
                dt.items.add(file);
            }
        }

        input.files = dt.files;
        
        // Trigger Event ke Livewire dengan file baru
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function compressImage(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const MAX_DIM = 1920;
                    const scale = Math.min(1, MAX_DIM / Math.max(img.width, img.height));
                    const canvas = document.createElement('canvas');
                    canvas.width = Math.round(img.width * scale);
                    canvas.height = Math.round(img.height * scale);
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    canvas.toBlob(function(blob) {
                        if (blob) {
                            resolve(blob);
                        } else {
                            reject(new Error("Canvas compression failed"));
                        }
                    }, 'image/jpeg', 0.82);
                };
                img.onerror = reject;
                img.src = e.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }
    </script>
</div>
