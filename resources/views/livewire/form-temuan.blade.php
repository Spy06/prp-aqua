<div>
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-lg font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-lg font-medium">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Container -->
    <section class="bg-white dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-700/50 overflow-hidden flex flex-col shadow-xl">
        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-b border-slate-200 dark:border-slate-700/50">
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-cyan-600 dark:text-cyan-400" style="font-variation-settings: 'FILL' 1;">add_circle</span>
                Buat Temuan Baru
            </h3>
        </div>
        
        <form wire:submit="submit" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="flex flex-col gap-5">
                
                <!-- Departemen Dropdown -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider" for="departemen">Departemen <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model="departemen_id" id="departemen" class="w-full appearance-none bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-colors cursor-pointer shadow-sm">
                            <option value="">Pilih Departemen</option>
                            @foreach($departemens as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">arrow_drop_down</span>
                    </div>
                    @error('departemen_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Sub Area Input -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider" for="subarea">Sub Area <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="sub_area" id="subarea" placeholder="Contoh: Line A Packaging" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-colors shadow-sm" />
                    @error('sub_area') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Klausul PRP Dropdown -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider" for="klausul">Klausul PRP <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model="klausul_id" id="klausul" class="w-full appearance-none bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-colors cursor-pointer shadow-sm">
                            <option value="">Pilih Klausul Referensi</option>
                            @foreach($klausuls as $klausul)
                                <option value="{{ $klausul->id }}">{{ $klausul->kode_klausul }} — {{ $klausul->nama_klausul }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">arrow_drop_down</span>
                    </div>
                    @error('klausul_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Upload Foto -->
                <div class="flex flex-col gap-1.5 mt-2">
                    <label class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider">Bukti Foto <span class="text-red-500">*</span></label>
                    
                    <div class="relative w-full border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-cyan-500 dark:hover:border-cyan-400 bg-slate-50 dark:bg-slate-900/50 rounded-xl p-6 flex flex-col items-center justify-center gap-3 transition-colors cursor-pointer group">
                        <input type="file" wire:model="foto_temuan" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        
                        @if ($foto_temuan)
                            <img src="{{ $foto_temuan->temporaryUrl() }}" class="h-32 object-contain rounded-md shadow-sm">
                        @else
                            <div class="w-14 h-14 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-cyan-600 dark:text-cyan-400 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">add_a_photo</span>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-cyan-700 dark:text-cyan-400">Ambil Foto atau Unggah</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Format: JPG, PNG (Max 5MB)</p>
                            </div>
                        @endif
                    </div>
                    <div wire:loading wire:target="foto_temuan" class="text-xs text-cyan-600 mt-1 font-medium">Mengunggah foto...</div>
                    @error('foto_temuan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="flex flex-col gap-5">
                
                <!-- Deskripsi Temuan -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider" for="deskripsi">Deskripsi Temuan <span class="text-red-500">*</span></label>
                    <textarea wire:model="deskripsi" id="deskripsi" placeholder="Jelaskan secara detail apa yang ditemukan..." rows="4" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-colors resize-none shadow-sm"></textarea>
                    @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Saran & Masukan -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider" for="saran">Saran & Tindakan Perbaikan (Opsional)</label>
                    <textarea wire:model="saran" id="saran" placeholder="Saran tindakan pencegahan atau perbaikan..." rows="3" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-colors resize-none shadow-sm"></textarea>
                    @error('saran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Pencarian PIC -->
                <div class="flex flex-col gap-1.5 relative">
                    <label class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider" for="pic">Person In Charge (PIC) <span class="text-red-500">*</span></label>
                    
                    @if($pic_id)
                        <div class="flex items-center justify-between p-3.5 border border-green-300 bg-green-50 rounded-xl dark:bg-green-900/20 dark:border-green-800 shadow-sm">
                            <span class="text-sm font-bold text-green-800 dark:text-green-300 flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                {{ $picSearch }}
                            </span>
                            <button type="button" wire:click="clearPic" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 font-bold px-2 py-1 rounded hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">Batal</button>
                        </div>
                    @else
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-slate-400">person_search</span>
                            <input wire:model.live.debounce.300ms="picSearch" id="pic" placeholder="Cari nama atau NIK PIC..." type="text" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl pl-12 pr-4 py-3 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-colors shadow-sm" />
                        </div>
                        
                        @if(count($picResults) > 0)
                            <div class="absolute top-full mt-2 w-full z-20 bg-white dark:bg-slate-800 shadow-2xl rounded-xl border border-slate-200 dark:border-slate-700 max-h-60 overflow-auto">
                                <ul class="py-2">
                                    @foreach($picResults as $result)
                                        <li>
                                            <button type="button" wire:click="selectPic({{ $result->id }}, '{{ addslashes($result->name ?? $result->nik) }}')" 
                                                class="w-full text-left px-5 py-3 text-sm text-slate-700 dark:text-slate-200 hover:bg-cyan-50 dark:hover:bg-cyan-900/30 transition-colors border-b border-slate-100 dark:border-slate-700/50 last:border-0">
                                                <div class="font-bold text-base">{{ $result->name ?? 'User' }}</div>
                                                <div class="text-xs text-slate-500 font-medium mt-0.5">NIK: {{ $result->nik }}</div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif(strlen($picSearch) >= 2)
                            <div class="absolute top-full mt-2 w-full z-20 bg-white dark:bg-slate-800 shadow-2xl rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-sm text-slate-500 font-medium text-center">
                                Tidak ada PIC yang ditemukan.
                            </div>
                        @endif
                    @endif
                    @error('pic_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="md:col-span-2 flex justify-end gap-4 pt-6 border-t border-slate-200 dark:border-slate-700/50 mt-4">
                <button type="submit" wire:loading.attr="disabled" class="w-full md:w-auto px-8 py-3.5 rounded-xl font-bold text-white bg-cyan-600 hover:bg-cyan-700 dark:bg-cyan-500 dark:hover:bg-cyan-400 dark:text-cyan-950 transition-all shadow-lg shadow-cyan-600/20 flex items-center justify-center gap-2 active:scale-[0.98]">
                    <span wire:loading.remove wire:target="submit" class="material-symbols-outlined">send</span>
                    <span wire:loading.remove wire:target="submit">Kirim Laporan</span>
                    <span wire:loading wire:target="submit">Menyimpan...</span>
                </button>
            </div>
        </form>
    </section>
</div>
