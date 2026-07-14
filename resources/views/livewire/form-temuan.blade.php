<div class="bg-white dark:bg-[#1E3A4A] rounded-xl shadow-sm border border-cyan-100 dark:border-transparent p-4 md:p-8">
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 md:mb-8 border-b border-zinc-100 dark:border-[#2c4a5c] pb-4">
        <h2 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white mb-1 flex items-center gap-2">
            <svg class="w-6 h-6 text-teal-700 dark:text-[#00D4FF] hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Buat Temuan Baru
        </h2>
        <p class="text-sm text-zinc-500 dark:text-[#b0bec5]">Laporkan kondisi ketidaksesuaian PRP di area produksi.</p>
    </div>

    <form wire:submit="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
            <!-- Left Column -->
            <div class="space-y-5">
                <!-- Departemen -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-[#e0f7fa] uppercase tracking-wide mb-1">Departemen <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model="departemen_id" class="block w-full rounded-lg border-zinc-200 dark:border-[#2c4a5c] bg-white dark:bg-[#0B141A] dark:text-white shadow-sm focus:border-teal-500 dark:focus:border-[#00D4FF] focus:ring-teal-500 dark:focus:ring-[#00D4FF] text-sm py-2.5 appearance-none">
                            <option value="">Pilih Departemen</option>
                            @foreach($departemens as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500 dark:text-white">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('departemen_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Sub Area -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-[#e0f7fa] uppercase tracking-wide mb-1">Sub Area <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="sub_area" placeholder="Contoh: Line A Packaging"
                        class="block w-full rounded-lg border-zinc-200 dark:border-[#2c4a5c] bg-white dark:bg-[#0B141A] dark:text-white dark:placeholder-[#607D8B] shadow-sm focus:border-teal-500 dark:focus:border-[#00D4FF] focus:ring-teal-500 dark:focus:ring-[#00D4FF] text-sm py-2.5">
                    @error('sub_area') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Klausul PRP -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-[#e0f7fa] uppercase tracking-wide mb-1">Klausul PRP <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model="klausul_id" class="block w-full rounded-lg border-zinc-200 dark:border-[#2c4a5c] bg-white dark:bg-[#0B141A] dark:text-white shadow-sm focus:border-teal-500 dark:focus:border-[#00D4FF] focus:ring-teal-500 dark:focus:ring-[#00D4FF] text-sm py-2.5 appearance-none">
                            <option value="">Pilih Klausul Referensi</option>
                            @foreach($klausuls as $klausul)
                                <option value="{{ $klausul->id }}">{{ $klausul->kode_klausul }} — {{ $klausul->nama_klausul }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500 dark:text-white">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('klausul_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Foto Temuan (Drag & Drop UI) -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-[#e0f7fa] uppercase tracking-wide mb-1">Bukti Foto <span class="text-red-500">*</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-cyan-100 dark:border-[#2c4a5c] border-dashed rounded-lg bg-cyan-50/50 dark:bg-[#0B141A] hover:bg-cyan-50 dark:hover:bg-[#13242e] transition-colors relative cursor-pointer">
                        <input type="file" wire:model="foto_temuan" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-2 text-center relative z-0">
                            <div class="mx-auto h-12 w-12 text-teal-700 dark:text-[#00D4FF] flex justify-center items-center bg-white dark:bg-[#1E3A4A] rounded-full shadow-sm mb-2">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m-3-3h6"></path></svg>
                            </div>
                            <div class="text-sm font-bold text-teal-800 dark:text-white">
                                <span>Ambil Foto atau Unggah</span>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-[#8ca4b3]">Format: JPG, PNG (Max 5MB)</p>
                            <div wire:loading wire:target="foto_temuan" class="text-sm text-teal-600 dark:text-[#00D4FF] mt-2 font-medium">Mengunggah...</div>
                        </div>
                    </div>
                    @error('foto_temuan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    
                    @if ($foto_temuan)
                        <div class="mt-3">
                            <p class="text-xs font-bold text-zinc-500 mb-2">Preview:</p>
                            <img src="{{ $foto_temuan->temporaryUrl() }}" class="h-24 object-cover rounded-lg border border-zinc-200">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <!-- Deskripsi -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-[#e0f7fa] uppercase tracking-wide mb-1">Deskripsi Temuan <span class="text-red-500">*</span></label>
                    <textarea wire:model="deskripsi" rows="3" placeholder="Jelaskan secara detail apa yang ditemukan..."
                        class="block w-full rounded-lg border-zinc-200 dark:border-[#2c4a5c] bg-white dark:bg-[#0B141A] dark:text-white dark:placeholder-[#607D8B] shadow-sm focus:border-teal-500 dark:focus:border-[#00D4FF] focus:ring-teal-500 dark:focus:ring-[#00D4FF] text-sm py-2.5"></textarea>
                    @error('deskripsi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Saran & Masukan -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-[#e0f7fa] uppercase tracking-wide mb-1">Saran & Tindakan Perbaikan (Opsional)</label>
                    <textarea wire:model="saran" rows="3" placeholder="Saran tindakan pencegahan atau perbaikan..."
                        class="block w-full rounded-lg border-zinc-200 dark:border-[#2c4a5c] bg-white dark:bg-[#0B141A] dark:text-white dark:placeholder-[#607D8B] shadow-sm focus:border-teal-500 dark:focus:border-[#00D4FF] focus:ring-teal-500 dark:focus:ring-[#00D4FF] text-sm py-2.5"></textarea>
                    @error('saran') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Pencarian PIC -->
                <div class="relative">
                    <label class="block text-xs font-bold text-zinc-700 dark:text-[#e0f7fa] uppercase tracking-wide mb-1">Person In Charge (PIC) <span class="text-red-500">*</span></label>
                    
                    @if($pic_id)
                        <div class="mt-1 flex items-center justify-between p-2.5 border border-teal-200 dark:border-[#00D4FF]/30 bg-teal-50 dark:bg-[#00D4FF]/10 rounded-lg">
                            <div class="flex items-center gap-2 text-sm font-medium text-teal-800 dark:text-[#00D4FF]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $picSearch }}
                            </div>
                            <button type="button" wire:click="clearPic" class="text-xs font-bold text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">GANTI</button>
                        </div>
                        <input type="hidden" wire:model="pic_id">
                    @else
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400 dark:text-[#607D8B]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 7v6"></path></svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="picSearch" placeholder="Cari nama atau ID PIC..."
                                class="block w-full pl-10 pr-3 py-2.5 rounded-lg border-zinc-200 dark:border-[#2c4a5c] bg-white dark:bg-[#0B141A] dark:text-white dark:placeholder-[#607D8B] shadow-sm focus:border-teal-500 dark:focus:border-[#00D4FF] focus:ring-teal-500 dark:focus:ring-[#00D4FF] text-sm">
                        </div>
                        
                        @if(count($picResults) > 0)
                            <div class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-lg border border-zinc-200 max-h-60 overflow-auto">
                                <ul class="py-1">
                                    @foreach($picResults as $result)
                                        <li>
                                            <button type="button" wire:click="selectPic({{ $result->id }}, '{{ addslashes($result->name ?? $result->nik) }}')" 
                                                class="w-full text-left px-4 py-2 text-sm text-zinc-700 hover:bg-cyan-50">
                                                <div class="font-bold text-teal-800">{{ $result->name ?? 'User' }}</div>
                                                <div class="text-xs text-zinc-500">NIK: {{ $result->nik }}</div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif(strlen($picSearch) >= 2)
                            <div class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-lg border border-zinc-200 p-3 text-sm text-zinc-500">
                                Tidak ada PIC yang ditemukan.
                            </div>
                        @endif
                    @endif
                    @error('pic_id') <span class="text-red-500 text-xs mt-1 block">PIC wajib dipilih.</span> @enderror
                </div>
            </div>
        </div>

        <div class="pt-6 mt-6 border-t border-zinc-100 dark:border-[#2c4a5c] flex flex-col md:flex-row justify-end items-center gap-3">
            <button type="button" class="w-full md:w-auto px-6 py-2.5 bg-cyan-100 dark:bg-[#2c4a5c] text-teal-800 dark:text-white font-bold text-sm rounded-lg hover:bg-cyan-200 dark:hover:bg-[#1E3A4A] transition-colors">
                Batal
            </button>
            <button type="submit" wire:loading.attr="disabled"
                class="w-full md:w-auto flex justify-center items-center gap-2 px-6 py-2.5 bg-teal-800 dark:bg-[#00D4FF] text-white dark:text-[#0B141A] font-bold text-sm rounded-lg hover:bg-teal-900 dark:hover:bg-[#00a6c7] transition-colors shadow-md disabled:opacity-50">
                <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Laporan
                </span>
                <span wire:loading wire:target="submit">Memproses...</span>
            </button>
        </div>
    </form>
</div>
