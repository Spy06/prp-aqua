<div class="bcard fu" style="box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-radius: 16px; overflow: visible !important;">
    {{-- Card Header --}}
    <div class="bcard-header" style="justify-content:space-between; border-bottom: 1px solid var(--bbor); padding: 20px 24px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="bcard-hicon" style="background:var(--bp-light); width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <span class="material-symbols-outlined fil" style="color:var(--bp);font-size:22px;">edit_note</span>
            </div>
            <div>
                <div style="font-size:16px;font-weight:700;color:var(--btxt);">Form Laporan Temuan Baru</div>
                <div style="font-size:12px;color:var(--btxt2);">Isi semua field yang bertanda * untuk melaporkan ketidaksesuaian PRP</div>
            </div>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="bcard-body" style="padding:24px; overflow: visible !important;">

        @if(session()->has('success'))
            <div style="padding:14px 18px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:12px;color:#2e7d32;font-weight:600;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session()->has('error'))
            <div style="padding:14px 18px;background:#ffebee;border:1px solid #ef9a9a;border-radius:12px;color:#c62828;font-weight:600;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="submit" style="display:flex;flex-direction:column;gap:20px;">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

                {{-- ── KOLOM KIRI ── --}}
                <div style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Tanggal Temuan --}}
                    <div>
                        <label class="blabel" for="tgl">Tanggal Temuan <span style="color:var(--error);">*</span></label>
                        <input type="date" wire:model="tanggal_temuan" id="tgl" class="binput">
                        @error('tanggal_temuan') <span class="berr">{{ $message }}</span> @enderror
                    </div>

                    {{-- Departemen --}}
                    <div>
                        <label class="blabel" for="dept">Departemen <span style="color:var(--error);">*</span></label>
                        <select wire:model.live="departemen_id" id="dept" class="binput">
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
                        <select wire:model.live="sub_area" id="subarea" class="binput"
                            {{ empty($departemen_id) ? 'disabled style=cursor:not-allowed;opacity:0.65;background:var(--bsur);' : '' }}>
                            <option value="">{{ empty($departemen_id) ? 'Pilih Departemen Terlebih Dahulu' : 'Pilih Sub Area' }}</option>
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
                        <label class="blabel">Upload Foto Temuan <span style="font-size:11px;font-weight:400;color:var(--btxt2);">(Opsional)</span></label>
                        <div style="border:2px dashed var(--bbor);border-radius:12px;padding:20px;display:flex;flex-direction:column;align-items:center;justify-content:center;background:var(--bsur);min-height:160px;gap:12px;text-align:center;">
                            {{-- Input tanpa wire:model — upload dikendalikan JS kompresi --}}
                            <input
                                type="file"
                                id="foto-input-tunggal"
                                accept="image/jpeg,image/jpg,image/png,image/webp"
                                style="display:none;"
                                onchange="siveraFotoHandler(this)"
                            />

                            {{-- Preview area: dikontrol via JS, fallback ke Livewire state --}}
                            <div id="sivera-foto-preview" style="display:none;flex-direction:column;align-items:center;width:100%;">
                                <img id="sivera-foto-img" src="" style="max-width:180px;max-height:130px;border-radius:10px;object-fit:contain;margin:0 auto;display:block;box-shadow:0 4px 12px rgba(0,0,0,0.1);border:1px solid var(--bbor);" />
                                <span id="sivera-foto-name" style="display:block;font-size:11px;color:var(--btxt2);margin-top:6px;text-align:center;word-break:break-all;max-width:220px;"></span>
                            </div>

                            @if ($foto_temuan)
                                <div id="sivera-foto-server" style="display:flex;flex-direction:column;align-items:center;width:100%;">
                                    <img src="{{ $foto_temuan->temporaryUrl() }}" style="max-width:180px;max-height:130px;border-radius:10px;object-fit:contain;margin:0 auto;display:block;box-shadow:0 4px 12px rgba(0,0,0,0.1);border:1px solid var(--bbor);" />
                                    <span style="display:block;font-size:11px;color:var(--btxt2);margin-top:6px;text-align:center;word-break:break-all;max-width:220px;">{{ $foto_temuan->getClientOriginalName() }}</span>
                                </div>
                            @else
                                <div id="sivera-foto-placeholder" style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                                    <span class="material-symbols-outlined" style="font-size:38px;color:var(--btxt2);opacity:0.6;">cloud_upload</span>
                                    <div style="font-size:13px;font-weight:600;color:var(--btxt);">Pilih Sumber Foto</div>
                                </div>
                            @endif

                            <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                                <button type="button"
                                    onclick="var el=document.getElementById('foto-input-tunggal');el.setAttribute('capture','environment');el.value='';el.click();"
                                    style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bp);color:#fff;font-size:12px;font-weight:600;border-radius:8px;border:none;">
                                    <span class="material-symbols-outlined" style="font-size:16px;">photo_camera</span>
                                    Ambil Foto
                                </button>
                                <button type="button"
                                    onclick="var el=document.getElementById('foto-input-tunggal');el.removeAttribute('capture');el.value='';el.click();"
                                    style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bsur);color:var(--btxt);font-size:12px;font-weight:600;border-radius:8px;border:1.5px solid var(--bbor);">
                                    <span class="material-symbols-outlined" style="font-size:16px;">photo_library</span>
                                    Dari Galeri
                                </button>
                            </div>
                            <p id="sivera-foto-hint" style="font-size:11.5px;color:var(--btxt2);margin:4px 0 0 0;text-align:center;">Otomatis dikompres jika &gt; 3MB &bull; JPG, PNG, WebP</p>
                        </div>
                        @error('foto_temuan') <span class="berr" style="display:block;margin-top:6px;">{{ $message }}</span> @enderror
                        <div id="sivera-upload-progress" style="display:none;font-size:12px;color:var(--bp);margin-top:6px;align-items:center;gap:6px;">
                            <span class="material-symbols-outlined" style="font-size:16px;animation:spin 1s linear infinite;">sync</span>
                            <span id="sivera-upload-label">Memproses foto...</span>
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

                    {{-- PIC Search (dengan auto-suggest real-time & smooth scrollbar) --}}
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
                                <input wire:model.live.debounce.150ms="picSearch" id="pic"
                                    placeholder="Cari nama atau NIK PIC..."
                                    type="text" class="binput" style="padding-left:38px;" />
                            </div>

                            @if(count($picResults) > 0)
                                <div class="pic-dropdown-scroll" style="position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:9999;background:var(--bcard, #ffffff);border:1.5px solid var(--bp, #1565c0);border-radius:12px;box-shadow:0 12px 32px rgba(0,0,0,0.22);max-height:250px;overflow-y:auto;scrollbar-width:thin;-webkit-overflow-scrolling:touch;">
                                    @foreach($picResults as $result)
                                        <button type="button"
                                            wire:click="selectPic({{ $result->id }}, '{{ addslashes($result->name ?? $result->nik) }}')"
                                            style="width:100%;text-align:left;padding:12px 14px;background:none;border:none;border-bottom:1px solid var(--bbor, #e4e4e7);cursor:pointer;font-family:inherit;transition:background .15s;display:block;"
                                            onmouseover="this.style.background='var(--bp-light, #e3f2fd)'" onmouseout="this.style.background='none'">
                                            <div style="font-size:13.5px;font-weight:600;color:var(--btxt, #18181b);">{{ is_array($result) ? ($result['name'] ?? 'User') : ($result->name ?? 'User') }}</div>
                                            @php
                                                $deptName = is_array($result)
                                                    ? ($result['karyawan']['departemen']['nama_departemen'] ?? ($result['nik'] ?? '-'))
                                                    : ($result->karyawan->departemen->nama_departemen ?? ($result->nik ?? '-'));
                                            @endphp
                                            <div style="font-size:12px;color:var(--btxt2, #71717a);margin-top:2px;">Departemen: {{ $deptName }}</div>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif(strlen(trim($picSearch)) >= 1)
                                <div style="position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:9999;background:var(--bcard, #ffffff);border:1px solid var(--bbor);border-radius:12px;padding:14px;text-align:center;font-size:13px;color:var(--btxt2);box-shadow:0 12px 32px rgba(0,0,0,0.22);">
                                    Tidak ada PIC yang ditemukan dengan NIK / nama tersebut.
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

    <style>
        .pic-dropdown-scroll::-webkit-scrollbar { width: 6px; }
        .pic-dropdown-scroll::-webkit-scrollbar-track { background: #f4f4f5; border-radius: 8px; }
        .pic-dropdown-scroll::-webkit-scrollbar-thumb { background: #a1a1aa; border-radius: 8px; }
        .pic-dropdown-scroll::-webkit-scrollbar-thumb:hover { background: #71717a; }

        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @media (max-width: 640px) {
            form > div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; }
        }
    </style>

    <script>
    /**
     * SIVERA — Handler upload foto dengan kompresi otomatis.
     * - File ≤ 3MB : langsung upload tanpa kompresi
     * - File > 3MB : kompres via Canvas hingga ≤ 2MB lalu upload
     */
    function siveraFotoHandler(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const MAX_BYTES = 3 * 1024 * 1024;   // 3 MB — batas user
        const TARGET_BYTES = 2 * 1024 * 1024; // 2 MB — batas aman PHP

        _siveraShowProgress('Memproses foto...');

        // Tampilkan preview lokal segera (UX responsif)
        _siveraLocalPreview(file);

        if (file.size <= MAX_BYTES) {
            // File sudah kecil — upload langsung
            _siveraDoUpload(file);
        } else {
            // File besar — kompres dulu
            _siveraCompress(file, TARGET_BYTES, function(compressed) {
                _siveraDoUpload(compressed);
            });
        }
    }

    function _siveraLocalPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('sivera-foto-preview');
            var img     = document.getElementById('sivera-foto-img');
            var name    = document.getElementById('sivera-foto-name');
            var ph      = document.getElementById('sivera-foto-placeholder');
            if (preview && img) {
                img.src  = e.target.result;
                if (name) name.textContent = file.name;
                preview.style.display = 'flex';
            }
            if (ph) ph.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    function _siveraCompress(file, targetBytes, callback) {
        _siveraShowProgress('Mengompres foto besar...');
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = new Image();
            img.onload = function() {
                var MAX_DIM = 1920;
                var scale   = Math.min(1, MAX_DIM / Math.max(img.width, img.height));
                var canvas  = document.createElement('canvas');
                canvas.width  = Math.round(img.width  * scale);
                canvas.height = Math.round(img.height * scale);
                canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);

                var quality = 0.85;
                var tryNext = function() {
                    canvas.toBlob(function(blob) {
                        if (!blob) { callback(file); return; }
                        if (blob.size <= targetBytes || quality < 0.3) {
                            var out = new File(
                                [blob],
                                file.name.replace(/\.[^.]+$/, '_compressed.jpg'),
                                { type: 'image/jpeg', lastModified: Date.now() }
                            );
                            callback(out);
                        } else {
                            quality -= 0.1;
                            tryNext();
                        }
                    }, 'image/jpeg', quality);
                };
                tryNext();
            };
            img.onerror = function() { callback(file); };
            img.src = e.target.result;
        };
        reader.onerror = function() { callback(file); };
        reader.readAsDataURL(file);
    }

    function _siveraDoUpload(file) {
        _siveraShowProgress('Mengunggah foto...');
        // Gunakan ID komponen form-temuan yang diinjeksi dari PHP, bukan querySelector
        var componentId = '{{ $this->getId() }}';
        var component   = Livewire.find(componentId);
        if (!component) { _siveraHideProgress(); return; }

        component.upload(
            'foto_temuan',
            file,
            function() { _siveraHideProgress(); }, // sukses
            function(err) {                          // gagal
                _siveraHideProgress();
                console.error('[SIVERA] Upload gagal:', err);
            },
            function(e) {                            // progress
                var pct = e.detail ? Math.round(e.detail.progress) : '...';
                _siveraShowProgress('Mengunggah foto ' + pct + '%...');
            }
        );
    }

    function _siveraShowProgress(msg) {
        var el = document.getElementById('sivera-upload-progress');
        var lb = document.getElementById('sivera-upload-label');
        if (el) el.style.display = 'flex';
        if (lb) lb.textContent = msg || 'Memproses...';
    }

    function _siveraHideProgress() {
        var el = document.getElementById('sivera-upload-progress');
        if (el) el.style.display = 'none';
    }
    </script>
</div>
