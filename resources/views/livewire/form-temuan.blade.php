<div>
    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div class="balert balert-success fu" style="margin-bottom:20px;">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="balert balert-error fu" style="margin-bottom:20px;">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="bph fu">
        <div>
            <h2 class="bph-title">Buat Laporan Temuan</h2>
            <p class="bph-sub">Laporkan kondisi ketidaksesuaian PRP di area produksi.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bcard fu1">
        <div class="bcard-header">
            <div class="bcard-hicon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:20px;">edit_note</span>
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--btxt);">Form Laporan Baru</div>
                <div style="font-size:12px;color:var(--btxt2);">Isi semua field yang bertanda *</div>
            </div>
        </div>

        <div class="bcard-body">
            <form wire:submit="submit">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

                    {{-- ── KOLOM KIRI ── --}}
                    <div style="display:flex;flex-direction:column;gap:16px;">

                        {{-- Departemen --}}
                        <div>
                            <label class="blabel" for="departemen">Departemen <span style="color:var(--error);">*</span></label>
                            <select wire:model.live="departemen_id" id="departemen" class="binput">
                                <option value="">Pilih Departemen</option>
                                @foreach($departemens as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                                @endforeach
                            </select>
                            @error('departemen_id') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Sub Area --}}
                        <div>
                            <label class="blabel" for="subarea">Sub Area <span style="color:var(--error);">*</span></label>
                            <select wire:model.live="sub_area" id="subarea" class="binput" {{ empty($departemen_id) ? 'disabled' : '' }}>
                                <option value="">Pilih Sub Area</option>
                                @foreach($subAreas as $area)
                                    <option value="{{ $area->nama_sub_area }}">{{ $area->nama_sub_area }}</option>
                                @endforeach
                            </select>
                            @error('sub_area') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Detail Sub Area (if Others) --}}
                        @if($sub_area === 'Others')
                        <div>
                            <label class="blabel" for="detail_sub_area">Detail Sub Area <span style="color:var(--error);">*</span></label>
                            <input type="text" wire:model="detail_sub_area" id="detail_sub_area" class="binput" placeholder="Masukkan nama detail area spesifik...">
                            @error('detail_sub_area') <span class="berr">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        {{-- Klausul PRP --}}
                        <div>
                            <label class="blabel" for="klausul">Klausul PRP <span style="color:var(--error);">*</span></label>
                            <select wire:model="klausul_id" id="klausul" class="binput">
                                <option value="">Pilih Klausul Referensi</option>
                                @foreach($klausuls as $klausul)
                                    <option value="{{ $klausul->id }}">{{ $klausul->kode_klausul }} — {{ $klausul->nama_klausul }}</option>
                                @endforeach
                            </select>
                            @error('klausul_id') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Upload Foto --}}
                        <div>
                            <label class="blabel">Upload Foto Temuan <span style="color:var(--error);">*</span></label>
                            <div style="border:2px dashed var(--bbor);border-radius:12px;padding:20px;display:flex;flex-direction:column;align-items:center;justify-content:center;background:var(--bsur);min-height:140px;gap:10px;">
                                <input type="file" id="foto-gallery" wire:model="foto_temuan" accept="image/*" style="display:none;" />
                                <input type="file" id="foto-camera" wire:model="foto_temuan" accept="image/*" capture="environment" style="display:none;" />

                                @if ($foto_temuan)
                                    <img src="{{ $foto_temuan->temporaryUrl() }}" style="max-height:120px;border-radius:8px;object-fit:contain;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                                @else
                                    <span class="material-symbols-outlined" style="font-size:36px;color:var(--btxt2);">add_a_photo</span>
                                    <p style="font-size:12.5px;color:var(--btxt2);margin:0;text-align:center;">Pilih metode upload foto temuan</p>
                                @endif

                                <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                                    <label for="foto-camera" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bp);color:#fff;font-size:12px;font-weight:600;border-radius:8px;transition:opacity .2s;">
                                        <span class="material-symbols-outlined" style="font-size:16px;">photo_camera</span>
                                        Ambil Foto
                                    </label>
                                    <label for="foto-gallery" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bsur);color:var(--btxt);font-size:12px;font-weight:600;border-radius:8px;border:1.5px solid var(--bbor);transition:opacity .2s;">
                                        <span class="material-symbols-outlined" style="font-size:16px;">photo_library</span>
                                        Dari Galeri
                                    </label>
                                </div>
                                <p style="font-size:11.5px;color:var(--btxt2);margin:4px 0 0 0;text-align:center;">Maksimal 3MB &bull; Format: JPG, PNG, WebP</p>
                            </div>
                            @error('foto_temuan') <span class="berr" style="display:block;margin-top:6px;">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="foto_temuan" style="font-size:12px;color:var(--bp);margin-top:6px;display:flex;align-items:center;gap:6px;">
                                <span class="material-symbols-outlined" style="font-size:16px;animation:spin 1s linear infinite;">sync</span>
                                Mengunggah foto...
                            </div>
                        </div>
                    </div>

                    {{-- ── KOLOM KANAN ── --}}
                    <div style="display:flex;flex-direction:column;gap:16px;">

                        {{-- Deskripsi --}}
                        <div>
                            <label class="blabel" for="deskripsi">Deskripsi Temuan <span style="color:var(--error);">*</span></label>
                            <textarea wire:model="deskripsi" id="deskripsi"
                                placeholder="Jelaskan secara detail kondisi ketidaksesuaian yang ditemukan..."
                                rows="5" class="binput" style="resize:vertical;"></textarea>
                            @error('deskripsi') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Saran --}}
                        <div>
                            <label class="blabel" for="saran">Saran & Masukan <span style="font-size:10px;font-weight:400;color:var(--btxt2);">(Opsional)</span></label>
                            <textarea wire:model="saran" id="saran" placeholder="Saran perbaikan kondisi ini..."
                                rows="3" class="binput" style="resize:vertical;"></textarea>
                            @error('saran') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- PIC Search --}}
                        <div style="position:relative;">
                            <label class="blabel" for="pic">Person In Charge (PIC) <span style="color:var(--error);">*</span></label>

                            @if($pic_id)
                                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border:1.5px solid #a5d6a7;border-radius:10px;background:#e8f5e9;">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:20px;">check_circle</span>
                                        <span style="font-size:13.5px;font-weight:600;color:#2e7d32;">{{ $picSearch }}</span>
                                    </div>
                                    <button type="button" wire:click="clearPic" style="font-size:12.5px;color:var(--error);background:none;border:none;cursor:pointer;font-weight:600;font-family:inherit;">
                                        Ganti
                                    </button>
                                </div>
                            @else
                                <div style="position:relative;">
                                    <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:18px;color:var(--btxt2);">search</span>
                                    <input wire:model.live.debounce.300ms="picSearch" id="pic"
                                        placeholder="Cari nama atau NIK PIC..."
                                        type="text" class="binput" style="padding-left:38px;" />
                                </div>

                                @if(count($picResults) > 0)
                                    <div style="position:absolute;top:100%;left:0;right:0;margin-top:4px;z-index:20;background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.1);overflow:hidden;max-height:200px;overflow-y:auto;">
                                        @foreach($picResults as $result)
                                            <button type="button"
                                                wire:click="selectPic({{ $result->id }}, '{{ addslashes($result->name ?? $result->nik) }}')"
                                                style="width:100%;text-align:left;padding:10px 14px;background:none;border:none;border-bottom:1px solid var(--bbor);cursor:pointer;font-family:inherit;transition:background .15s;"
                                                onmouseover="this.style.background='var(--bp-light)'" onmouseout="this.style.background='none'">
                                                <div style="font-size:13.5px;font-weight:600;color:var(--btxt);">{{ $result->name ?? 'User' }}</div>
                                                <div style="font-size:12px;color:var(--btxt2);margin-top:2px;">NIK: {{ $result->nik }}</div>
                                            </button>
                                        @endforeach
                                    </div>
                                @elseif(strlen($picSearch) >= 2)
                                    <div style="position:absolute;top:100%;left:0;right:0;margin-top:4px;z-index:20;background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;padding:14px;text-align:center;font-size:13px;color:var(--btxt2);">
                                        Tidak ada PIC yang ditemukan.
                                    </div>
                                @endif
                            @endif
                            @error('pic_id') <span class="berr">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div style="display:flex;flex-wrap:wrap;justify-content:flex-end;gap:10px;padding-top:20px;margin-top:20px;border-top:1px solid var(--bbor);">
                    <a href="{{ route('beranda') }}" wire:navigate class="bbtn bbtn-secondary">
                        <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                        Batal
                    </a>
                    <button type="submit" wire:loading.attr="disabled" class="bbtn bbtn-primary">
                        <span wire:loading.remove wire:target="submit" class="material-symbols-outlined" style="font-size:18px;">send</span>
                        <span wire:loading wire:target="submit" class="material-symbols-outlined" style="font-size:18px;animation:spin 1s linear infinite;">sync</span>
                        <span wire:loading.remove wire:target="submit">Kirim Laporan</span>
                        <span wire:loading wire:target="submit">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @media (max-width: 640px) {
            form > div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; }
        }
    </style>
</div>
