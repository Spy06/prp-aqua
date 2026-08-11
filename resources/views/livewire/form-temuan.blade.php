<div class="bcard fu" style="box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-radius: 16px; overflow: visible !important;" x-data="{ showConfirmModal: false }">
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

        <form @submit.prevent="showConfirmModal = true" style="display:flex;flex-direction:column;gap:20px;">

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
                        <label class="blabel">Upload Foto Temuan <span style="color:var(--error);">*</span></label>
                        <div style="border:2px dashed var(--bbor);border-radius:12px;padding:20px;display:flex;flex-direction:column;align-items:center;justify-content:center;background:var(--bsur);min-height:160px;gap:12px;text-align:center;">
                            <input
                                type="file"
                                id="foto-input-tunggal"
                                wire:model="foto_temuan"
                                accept="image/jpeg,image/jpg,image/png,image/webp"
                                style="display:none;"
                                onchange="handleFotoFileSelect(event)"
                            />

                            @if ($foto_temuan)
                                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;">
                                    <div style="position:relative;display:inline-block;">
                                        <img src="{{ $foto_temuan->temporaryUrl() }}" style="max-width:200px;max-height:140px;border-radius:10px;object-fit:contain;margin:0 auto;display:block;box-shadow:0 4px 12px rgba(0,0,0,0.1);border:1px solid var(--bbor);" />
                                        <button type="button" wire:click="$set('foto_temuan', null)" style="position:absolute;top:-8px;right:-8px;background:var(--error, #ef4444);color:#fff;border:none;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,0.2);" title="Hapus foto">
                                            <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                                        </button>
                                    </div>
                                    <span style="display:block;font-size:11.5px;font-weight:600;color:var(--bp);margin-top:6px;text-align:center;word-break:break-all;max-width:240px;">
                                        ✓ {{ $foto_temuan->getClientOriginalName() }}
                                    </span>
                                </div>
                            @else
                                <span class="material-symbols-outlined" style="font-size:38px;color:var(--btxt2);opacity:0.6;">cloud_upload</span>
                                <div style="font-size:13px;font-weight:600;color:var(--btxt);">Pilih Sumber Foto</div>
                            @endif

                            <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                                <button type="button"
                                    onclick="var el=document.getElementById('foto-input-tunggal');el.setAttribute('capture','environment');el.click();"
                                    style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bp);color:#fff;font-size:12px;font-weight:600;border-radius:8px;border:none;">
                                    <span class="material-symbols-outlined" style="font-size:16px;">photo_camera</span>
                                    Ambil Foto
                                </button>
                                <button type="button"
                                    onclick="var el=document.getElementById('foto-input-tunggal');el.removeAttribute('capture');el.click();"
                                    style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--bsur);color:var(--btxt);font-size:12px;font-weight:600;border-radius:8px;border:1.5px solid var(--bbor);">
                                    <span class="material-symbols-outlined" style="font-size:16px;">photo_library</span>
                                    Dari Galeri
                                </button>
                            </div>
                            <p style="font-size:11.5px;color:var(--btxt2);margin:4px 0 0 0;text-align:center;">Otomatis dikompres jika &gt; 3MB &bull; JPG, PNG, WebP</p>
                        </div>
                        @error('foto_temuan') <span class="berr" style="display:block;margin-top:6px;">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="foto_temuan" style="font-size:12px;color:var(--bp);margin-top:6px;display:flex;align-items:center;gap:6px;">
                            <span class="material-symbols-outlined" style="font-size:16px;animation:spin 1s linear infinite;">sync</span>
                            <span>Mengunggah foto...</span>
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
                <button type="button"
                    @click="showConfirmModal = true"
                    wire:loading.attr="disabled"
                    wire:target="submit,foto_temuan"
                    class="bbtn bbtn-primary">
                    <span wire:loading.remove wire:target="submit,foto_temuan" class="material-symbols-outlined" style="font-size:18px;">send</span>
                    <span wire:loading wire:target="submit,foto_temuan" class="material-symbols-outlined" style="font-size:18px;animation:spin 1s linear infinite;">sync</span>
                    <span wire:loading.remove wire:target="submit,foto_temuan">Kirim Laporan</span>
                    <span wire:loading wire:target="submit">Menyimpan...</span>
                    <span wire:loading wire:target="foto_temuan">Mengunggah Foto...</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Modal Dialog Konfirmasi Kirim Laporan --}}
    <template x-teleport="body">
        <div x-show="showConfirmModal"
             x-cloak
             style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:999999;background:rgba(15,23,42,0.65);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.outside="showConfirmModal = false"
                 style="position:absolute;top:0;bottom:0;left:0;right:0;margin:auto;height:fit-content;background:var(--bcard, #ffffff);border:1.5px solid var(--bbor, #e2e8f0);border-radius:18px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.35);max-width:440px;width:calc(100% - 40px);padding:28px 24px;text-align:center;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">

                <div style="width:60px;height:60px;border-radius:50%;background:var(--bp-light, #e3f2fd);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;box-shadow:0 4px 12px rgba(21,101,192,0.15);">
                    <span class="material-symbols-outlined fil" style="font-size:32px;color:var(--bp, #1565c0);">help</span>
                </div>

                <h3 style="font-size:18px;font-weight:700;color:var(--btxt, #1e293b);margin:0 0 8px 0;">Konfirmasi Kirim Laporan</h3>
                <p style="font-size:13.5px;color:var(--btxt2, #64748b);line-height:1.6;margin:0 0 24px 0;">
                    Apakah Anda sudah yakin data laporan dan foto temuan ketidaksesuaian PRP ini sudah sesuai dan siap dikirim?
                </p>

                <div style="display:flex;gap:10px;justify-content:center;">
                    <button type="button"
                            @click="showConfirmModal = false"
                            class="bbtn bbtn-secondary"
                            style="flex:1;justify-content:center;padding:10px 16px;border-radius:10px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                        Periksa Kembali
                    </button>
                    <button type="button"
                            @click="showConfirmModal = false; $wire.submit();"
                            class="bbtn bbtn-primary"
                            style="flex:1;justify-content:center;padding:10px 16px;border-radius:10px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">send</span>
                        Ya, Kirim Laporan
                    </button>
                </div>
            </div>
        </div>
    </template>

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
     * SIVERA — Auto-compress gambar jika > 3MB sebelum diupload ke Livewire.
     */
    function handleFotoFileSelect(event) {
        const input = event.target;
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const MAX_BYTES = 3 * 1024 * 1024; // 3 MB

        if (file.size <= MAX_BYTES) {
            return; // File <= 3MB langsung diproses oleh Livewire wire:model
        }

        // File > 3MB: hentikan propagasi awal dan kompres via Canvas
        event.stopImmediatePropagation();

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
                        const compressedFile = new File(
                            [blob],
                            file.name.replace(/\.[^.]+$/, '.jpg'),
                            { type: 'image/jpeg', lastModified: Date.now() }
                        );

                        const dt = new DataTransfer();
                        dt.items.add(compressedFile);
                        input.files = dt.files;

                        // Trigger event change Livewire dengan file yang telah dikompres
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }, 'image/jpeg', 0.82);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    </script>
</div>
