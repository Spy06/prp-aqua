<div style="display:flex;flex-direction:column;gap:20px;" id="tindak-lanjut-pic-form">

    {{-- Flash Messages --}}
    @if(session('status_success'))
        <div class="balert balert-success">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">check_circle</span>
            <span>{{ session('status_success') }}</span>
        </div>
    @endif
    @if(session('status_error'))
        <div class="balert balert-error">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">error</span>
            <span>{{ session('status_error') }}</span>
        </div>
    @endif
    @if(session('detail_success'))
        <div class="balert balert-info">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">info</span>
            <span>{{ session('detail_success') }}</span>
        </div>
    @endif
    @if(session('foto_success'))
        <div class="balert balert-success">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">photo</span>
            <span>{{ session('foto_success') }}</span>
        </div>
    @endif

    {{-- Status Saat Ini --}}
    @php
        $statusClass = match($currentStatus) {
            'open'              => 'sbadge-open',
            'in_progress'       => 'sbadge-progress',
            'closed_pending_qa' => 'sbadge-pending',
            'closed_acc'        => 'sbadge-closed',
            default             => 'sbadge-progress',
        };
        $statusText = match($currentStatus) {
            'open'              => 'Open',
            'in_progress'       => 'In Progress',
            'closed_pending_qa' => 'Closed Pending QA (Menunggu Verifikasi QA)',
            'closed_acc'        => 'Closed — Disetujui QA',
            default             => $currentStatus,
        };
    @endphp
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <span style="font-size:13px;font-weight:600;color:var(--btxt2);">Status tindak lanjut:</span>
        <span class="sbadge {{ $statusClass }}" style="font-size:11.5px;">{{ $statusText }}</span>
    </div>

    {{-- Section 1: Detail Tindak Lanjut --}}
    <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;padding-bottom:10px;border-bottom:1.5px solid var(--bbor);">
            <span class="material-symbols-outlined" style="color:var(--bp);font-size:18px;">edit_note</span>
            <span style="font-size:13px;font-weight:700;color:var(--btxt);text-transform:uppercase;letter-spacing:0.5px;">Detail Tindak Lanjut</span>
        </div>

        {{-- Tindakan / Action --}}
        <div style="margin-bottom:14px;">
            <label class="blabel" for="action">Tindakan Perbaikan <span style="color:var(--error);">*</span></label>
            <textarea id="action" wire:model="action" rows="4"
                placeholder="Jelaskan tindakan perbaikan yang dilakukan atau direncanakan..."
                class="binput" style="resize:vertical;"
                {{ $currentStatus === 'closed_pending_qa' || $currentStatus === 'closed_acc' ? 'disabled' : '' }}></textarea>
            @error('action') <span class="berr">{{ $message }}</span> @enderror
        </div>

        {{-- Due Date --}}
        <div style="margin-bottom:14px;">
            <label class="blabel" for="due_date">Due Date <span style="color:var(--error);">*</span></label>
            <input type="date" id="due_date" wire:model="due_date" class="binput"
                {{ $currentStatus === 'closed_pending_qa' || $currentStatus === 'closed_acc' ? 'disabled' : '' }} />
            @error('due_date') <span class="berr">{{ $message }}</span> @enderror
        </div>

        @if(!in_array($currentStatus, ['closed_pending_qa', 'closed_acc']))
        <div style="display:flex;justify-content:flex-end;">
            <button wire:click="simpanDetail" wire:loading.attr="disabled" id="btn-simpan-detail" class="bbtn bbtn-primary">
                <span wire:loading.remove wire:target="simpanDetail" class="material-symbols-outlined" style="font-size:18px;">save</span>
                <span wire:loading wire:target="simpanDetail" class="material-symbols-outlined" style="font-size:18px;animation:spin 1s linear infinite;">sync</span>
                Simpan Detail
            </button>
        </div>
        @endif
    </div>

    {{-- Section 2: Foto Bukti --}}
    <div style="padding-top:16px;border-top:1.5px solid var(--bbor);margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <span class="material-symbols-outlined" style="color:var(--bp);font-size:18px;">photo_camera</span>
            <span style="font-size:13px;font-weight:700;color:var(--btxt);text-transform:uppercase;letter-spacing:0.5px;">Foto Bukti</span>
            @if(empty($foto_bukti_path) && !$foto_bukti)
                <span style="font-size:11.5px;color:#e65100;font-weight:600;">(Wajib sebelum closed)</span>
            @else
                <span style="font-size:11.5px;color:#2e7d32;font-weight:600;">✓ Sudah diupload</span>
            @endif
        </div>

        @if($currentStatus !== 'closed_acc')
        <div>
            <div style="border:2px dashed var(--bbor);border-radius:12px;padding:20px;display:flex;flex-direction:column;align-items:center;justify-content:center;background:var(--bsur);min-height:140px;gap:10px;">
                <input type="file" id="foto-bukti-gallery" wire:model="foto_bukti" accept="image/*" style="display:none;" />
                <input type="file" id="foto-bukti-camera" wire:model="foto_bukti" accept="image/*" capture="environment" style="display:none;" />

                @if ($foto_bukti)
                    <img src="{{ $foto_bukti->temporaryUrl() }}" style="max-height:160px;border-radius:8px;object-fit:contain;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                @elseif ($foto_bukti_path)
                    <img src="{{ Storage::disk('public')->url($foto_bukti_path) }}" style="max-height:160px;border-radius:8px;object-fit:contain;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                @else
                    <span class="material-symbols-outlined" style="font-size:36px;color:var(--btxt2);">add_a_photo</span>
                    <p style="font-size:12.5px;color:var(--btxt2);margin:0;text-align:center;">Pilih metode upload foto bukti</p>
                @endif

                <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                    <label for="foto-bukti-camera" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bp);color:#fff;font-size:12px;font-weight:600;border-radius:8px;transition:opacity .2s;">
                        <span class="material-symbols-outlined" style="font-size:16px;">photo_camera</span>
                        Ambil Foto
                    </label>
                    <label for="foto-bukti-gallery" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bsur);color:var(--btxt);font-size:12px;font-weight:600;border-radius:8px;border:1.5px solid var(--bbor);transition:opacity .2s;">
                        <span class="material-symbols-outlined" style="font-size:16px;">photo_library</span>
                        Dari Galeri
                    </label>
                </div>
            </div>
            @error('foto_bukti') <span class="berr" style="display:block;margin-top:6px;">{{ $message }}</span> @enderror
            <div wire:loading wire:target="foto_bukti" style="font-size:12px;color:var(--bp);margin-top:6px;display:flex;align-items:center;gap:6px;">
                <span class="material-symbols-outlined" style="font-size:16px;animation:spin 1s linear infinite;">sync</span>
                Mengunggah foto...
            </div>
        </div>
        @else
            @if($foto_bukti_path)
            <div style="border-radius:10px;overflow:hidden;border:1px solid var(--bbor);">
                <img src="{{ Storage::disk('public')->url($foto_bukti_path) }}"
                     alt="Foto bukti tindak lanjut"
                     style="width:100%;max-height:220px;object-fit:contain;background:var(--bsur);" />
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
            <span style="padding:4px 6px;border-radius:6px;white-space:nowrap;{{ $currentStatus === 'closed_pending_qa' ? 'background:#f3e5f5;color:#6a1b9a;font-weight:700;' : 'background:var(--bsur);color:var(--btxt2);' }}">Pending QA</span>
            <span class="material-symbols-outlined" style="font-size:12px;color:var(--btxt2);flex-shrink:0;">arrow_forward</span>
            <span style="padding:4px 6px;border-radius:6px;white-space:nowrap;background:var(--bsur);color:var(--btxt2);">ACC (QA)</span>
        </div>

        {{-- Tombol Transisi --}}
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            @if($currentStatus === 'open')
                <button wire:click="ubahStatus('in_progress')" wire:loading.attr="disabled"
                    wire:confirm="Ubah status ke In Progress?"
                    id="btn-status-in-progress" class="bbtn bbtn-primary">
                    <span class="material-symbols-outlined" style="font-size:18px;">play_arrow</span>
                    Mulai Pengerjaan (In Progress)
                </button>
            @elseif($currentStatus === 'in_progress')
                <div style="width:100%;">
                    @if(empty($foto_bukti_path))
                    <div class="balert balert-warn" style="margin-bottom:10px;">
                        <span class="material-symbols-outlined" style="font-size:18px;flex-shrink:0;">warning</span>
                        <span><strong>⚠ Foto bukti wajib diupload</strong> sebelum menutup laporan untuk verifikasi QA.</span>
                    </div>
                    @endif

                    <button wire:click="ubahStatus('closed_pending_qa')" wire:loading.attr="disabled"
                        wire:confirm="Selesaikan dan kirim ke QA untuk verifikasi? Pastikan foto bukti sudah diupload."
                        id="btn-status-pending-qa" class="bbtn bbtn-purple"
                        {{ empty($foto_bukti_path) ? 'disabled' : '' }}>
                        <span class="material-symbols-outlined" style="font-size:18px;">send</span>
                        Selesai & Kirim ke QA
                    </button>

                    @if(empty($foto_bukti_path))
                    <p style="font-size:12px;color:#e65100;margin-top:8px;display:flex;align-items:center;gap:4px;">
                        <span class="material-symbols-outlined" style="font-size:15px;">lock</span>
                        Upload foto bukti terlebih dahulu untuk mengaktifkan tombol ini.
                    </p>
                    @endif
                </div>
            @elseif($currentStatus === 'closed_pending_qa')
                <div class="balert balert-info" style="width:100%;">
                    <span class="material-symbols-outlined" style="font-size:18px;flex-shrink:0;">schedule</span>
                    <span>Menunggu verifikasi QA. Anda tidak dapat mengubah status lebih lanjut.</span>
                </div>
            @endif
        </div>

        <p style="font-size:11.5px;color:var(--btxt2);margin-top:12px;display:flex;align-items:flex-start;gap:5px;flex-wrap:nowrap;">
            <span class="material-symbols-outlined" style="font-size:14px;flex-shrink:0;position:relative;top:2px;">lock</span>
            <span>Status <em>Closed ACC</em> hanya bisa diset oleh QA setelah verifikasi.</span>
        </p>
    </div>
    @endif

    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</div>
