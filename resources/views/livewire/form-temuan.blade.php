<div>
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tanggal Temuan -->
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tanggal Temuan</label>
                <input type="date" wire:model="tanggal_temuan" 
                    class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-300 sm:text-sm">
                @error('tanggal_temuan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Nama Penemu (Read Only) -->
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nama Penemu</label>
                <input type="text" value="{{ auth()->user()->karyawan->nama ?? auth()->user()->name }}" readonly disabled
                    class="mt-1 block w-full rounded-md border-zinc-300 bg-zinc-100 shadow-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-400 sm:text-sm">
            </div>

            <!-- Departemen -->
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Departemen (Area)</label>
                <select wire:model="departemen_id" 
                    class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-300 sm:text-sm">
                    <option value="">-- Pilih Departemen --</option>
                    @foreach($departemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>
                @error('departemen_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Sub Area -->
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Sub Area / Lokasi Spesifik</label>
                <input type="text" wire:model="sub_area" placeholder="Contoh: Jalur Evakuasi Gudang A"
                    class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-300 sm:text-sm">
                @error('sub_area') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Pencarian PIC -->
        <div class="relative">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">PIC Tindak Lanjut</label>
            
            @if($pic_id)
                <div class="mt-1 flex items-center justify-between p-2 border border-green-300 bg-green-50 rounded-md dark:bg-green-900/20 dark:border-green-800">
                    <span class="text-sm font-medium text-green-800 dark:text-green-300">Terpilih: {{ $picSearch }}</span>
                    <button type="button" wire:click="clearPic" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400">Batal/Ganti</button>
                </div>
                <input type="hidden" wire:model="pic_id">
            @else
                <input type="text" wire:model.live.debounce.300ms="picSearch" placeholder="Ketik NIK atau Nama PIC..."
                    class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-300 sm:text-sm">
                
                @if(count($picResults) > 0)
                    <div class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 shadow-lg rounded-md border border-zinc-200 dark:border-zinc-700 max-h-60 overflow-auto">
                        <ul class="py-1">
                            @foreach($picResults as $result)
                                <li>
                                    <button type="button" wire:click="selectPic({{ $result->id }}, '{{ addslashes($result->name ?? $result->nik) }}')" 
                                        class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/50">
                                        <div class="font-medium">{{ $result->name ?? 'User' }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">NIK: {{ $result->nik }}</div>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(strlen($picSearch) >= 2)
                    <div class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 shadow-lg rounded-md border border-zinc-200 dark:border-zinc-700 p-3 text-sm text-zinc-500">
                        Tidak ada user/PIC yang ditemukan.
                    </div>
                @endif
            @endif
            @error('pic_id') <span class="text-red-500 text-xs mt-1">PIC wajib dipilih dari daftar.</span> @enderror
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Deskripsi Temuan</label>
            <textarea wire:model="deskripsi" rows="3" placeholder="Jelaskan kondisi ketidaksesuaian secara detail..."
                class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-300 sm:text-sm"></textarea>
            @error('deskripsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Foto Temuan -->
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Foto Temuan (Max 5MB)</label>
            <input type="file" wire:model="foto_temuan" accept="image/*"
                class="mt-1 block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-400">
            @error('foto_temuan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            
            <div wire:loading wire:target="foto_temuan" class="text-sm text-indigo-600 mt-2">Mengunggah foto...</div>
            
            @if ($foto_temuan)
                <div class="mt-4">
                    <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Preview Foto:</p>
                    <img src="{{ $foto_temuan->temporaryUrl() }}" class="h-32 object-cover rounded-lg border border-zinc-200 dark:border-zinc-700">
                </div>
            @endif
        </div>

        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700 flex justify-end">
            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-800 disabled:opacity-50">
                <span wire:loading.remove wire:target="submit">Kirim Laporan & Beritahu PIC</span>
                <span wire:loading wire:target="submit">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
