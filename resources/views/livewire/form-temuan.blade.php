<div>
    @if (session()->has('success'))
        <div class="mb-lg p-md bg-[#e6f4ea] text-[#137333] border border-[#ceead6] rounded-lg font-body-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-lg p-md bg-error-container text-on-error-container border border-[var(--color-error)] rounded-lg font-body-md shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-lg">
        <h3 class="font-headline-lg text-headline-lg text-on-background mb-xs">Buat Temuan Baru</h3>
        <p class="font-body-md text-body-md text-on-surface-variant">Laporkan kondisi ketidaksesuaian PRP di area produksi.</p>
    </div>

    <!-- Form Container -->
    <div class="bg-surface rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] p-lg border border-outline-variant">
        <form wire:submit="submit" class="grid grid-cols-1 md:grid-cols-2 gap-lg">
            <!-- Left Column -->
            <div class="flex flex-col gap-md">
                
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="departemen">Departemen <span class="text-error">*</span></label>
                    <select wire:model="departemen_id" id="departemen" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm font-body-md text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                        <option value="">Pilih Departemen</option>
                        @foreach($departemens as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                        @endforeach
                    </select>
                    @error('departemen_id') <span class="font-body-sm text-error mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="subarea">Sub Area <span class="text-error">*</span></label>
                    <input wire:model="sub_area" id="subarea" placeholder="Contoh: Line A Packaging" type="text" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm font-body-md text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" />
                    @error('sub_area') <span class="font-body-sm text-error mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="klausul">Klausul PRP <span class="text-error">*</span></label>
                    <select wire:model="klausul_id" id="klausul" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm font-body-md text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                        <option value="">Pilih Klausul Referensi</option>
                        @foreach($klausuls as $klausul)
                            <option value="{{ $klausul->id }}">{{ $klausul->kode_klausul }} — {{ $klausul->nama_klausul }}</option>
                        @endforeach
                    </select>
                    @error('klausul_id') <span class="font-body-sm text-error mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface">Upload Foto <span class="text-error">*</span></label>
                    <div class="relative border-2 border-dashed border-outline-variant rounded-lg p-md flex flex-col items-center justify-center bg-surface-container-low hover:bg-surface-variant transition-colors cursor-pointer group">
                        {{-- Input tanpa capture (gallery) — hidden, dipicu tombol --}}
                        <input type="file" id="foto-gallery" wire:model="foto_temuan" accept="image/*"
                               class="hidden" />
                        {{-- Input dengan capture=environment (kamera belakang) — hidden, dipicu tombol --}}
                        <input type="file" id="foto-camera" wire:model="foto_temuan" accept="image/*"
                               capture="environment" class="hidden" />

                        @if ($foto_temuan)
                            <img src="{{ $foto_temuan->temporaryUrl() }}" class="h-32 object-contain rounded-md shadow-sm pointer-events-none">
                        @else
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">add_a_photo</span>
                            <p class="font-body-sm text-on-surface-variant text-sm mb-3">Pilih metode upload foto</p>
                        @endif

                        <div class="flex gap-2 mt-2">
                            <label for="foto-camera"
                                   class="cursor-pointer inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-white text-xs font-semibold rounded-lg hover:opacity-90 transition select-none">
                                <span class="material-symbols-outlined text-sm leading-none">photo_camera</span>
                                Ambil Foto
                            </label>
                            <label for="foto-gallery"
                                   class="cursor-pointer inline-flex items-center gap-1 px-3 py-1.5 bg-surface-container-high text-on-surface text-xs font-semibold rounded-lg border border-outline-variant hover:bg-surface-variant transition select-none">
                                <span class="material-symbols-outlined text-sm leading-none">photo_library</span>
                                Dari Galeri
                            </label>
                        </div>
                    </div>
                    @error('foto_temuan') <span class="font-body-sm text-error mt-1">{{ $message }}</span> @enderror
                </div>
                <div wire:loading wire:target="foto_temuan" class="font-body-sm text-primary mt-1">Mengunggah foto...</div>
            </div>

            <!-- Right Column -->
            <div class="flex flex-col gap-md">
                
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="deskripsi">Deskripsi Temuan <span class="text-error">*</span></label>
                    <textarea wire:model="deskripsi" id="deskripsi" placeholder="Jelaskan secara detail ketidaksesuaian yang ditemukan..." rows="4" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm font-body-md text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors resize-none"></textarea>
                    @error('deskripsi') <span class="font-body-sm text-error mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="saran">Saran & Masukan (Opsional)</label>
                    <textarea wire:model="saran" id="saran" placeholder="Saran perbaikan..." rows="3" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm font-body-md text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors resize-none"></textarea>
                    @error('saran') <span class="font-body-sm text-error mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-xs relative">
                    <label class="font-label-md text-label-md text-on-surface" for="pic">Person In Charge (PIC) <span class="text-error">*</span></label>
                    
                    @if($pic_id)
                        <div class="flex items-center justify-between p-3 border border-outline-variant bg-surface-container-low rounded-lg shadow-sm">
                            <span class="font-title-md text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary filled-icon">check_circle</span>
                                {{ $picSearch }}
                            </span>
                            <button type="button" wire:click="clearPic" class="font-label-md text-error hover:text-error-container transition-colors">Batal</button>
                        </div>
                    @else
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-md top-1/2 transform -translate-y-1/2 text-outline-variant">search</span>
                            <input wire:model.live.debounce.300ms="picSearch" id="pic" placeholder="Cari nama atau NIK PIC..." type="text" class="w-full bg-surface border border-outline-variant rounded-lg pl-xl pr-md py-sm font-body-md text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" />
                        </div>
                        
                        @if(count($picResults) > 0)
                            <div class="absolute top-full left-0 right-0 mt-1 z-20 bg-surface shadow-lg rounded-lg border border-outline-variant max-h-60 overflow-auto">
                                <ul class="py-2">
                                    @foreach($picResults as $result)
                                        <li>
                                            <button type="button" wire:click="selectPic({{ $result->id }}, '{{ addslashes($result->name ?? $result->nik) }}')" 
                                                class="w-full text-left px-md py-sm hover:bg-surface-container-low transition-colors border-b border-outline-variant/50 last:border-0">
                                                <div class="font-title-md text-on-surface">{{ $result->name ?? 'User' }}</div>
                                                <div class="font-body-sm text-on-surface-variant mt-1">NIK: {{ $result->nik }}</div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif(strlen($picSearch) >= 2)
                            <div class="absolute top-full left-0 right-0 mt-1 z-20 bg-surface shadow-lg rounded-lg border border-outline-variant p-4 font-body-sm text-on-surface-variant text-center">
                                Tidak ada PIC yang ditemukan.
                            </div>
                        @endif
                    @endif
                    @error('pic_id') <span class="font-body-sm text-error mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="md:col-span-2 flex justify-end gap-md pt-md border-t border-outline-variant mt-sm">
                <a href="{{ route('beranda') }}" wire:navigate class="px-lg py-sm rounded-lg font-title-md text-title-md text-primary bg-secondary-container hover:bg-surface-variant transition-colors flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" wire:loading.attr="disabled" class="px-lg py-sm rounded-lg font-title-md text-title-md text-on-primary bg-primary hover:opacity-90 transition-opacity shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] flex items-center gap-sm cursor-pointer disabled:opacity-70 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="submit" class="material-symbols-outlined">send</span>
                    <span wire:loading.remove wire:target="submit">Kirim Laporan</span>
                    <span wire:loading wire:target="submit">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
