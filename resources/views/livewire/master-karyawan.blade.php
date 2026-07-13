<div class="space-y-5" id="master-karyawan-container">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flex items-center gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm">
            <flux:icon.check-circle variant="solid" class="w-4 h-4 shrink-0" />
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 text-sm">
            <flux:icon.exclamation-triangle variant="solid" class="w-4 h-4 shrink-0" />
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Master Karyawan</h2>
        <button wire:click="openCreate" id="btn-tambah-karyawan"
                class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
            <flux:icon.plus variant="outline" class="w-4 h-4" />
            Tambah Karyawan
        </button>
    </div>

    {{-- Form Tambah/Edit --}}
    @if($showForm)
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5">
            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4">
                {{ $editingNik ? "Edit Karyawan (NIK: {$editingNik})" : 'Tambah Karyawan Baru' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- NIK --}}
                <div>
                    <label for="form-nik" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">NIK <span class="text-red-500">*</span></label>
                    <input type="text" id="form-nik" wire:model="nik"
                           {{ $editingNik ? 'disabled' : '' }}
                           placeholder="Contoh: 2024001"
                           class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 disabled:bg-zinc-100 dark:disabled:bg-zinc-800" />
                    @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Nama --}}
                <div>
                    <label for="form-nama" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="form-nama" wire:model="nama" placeholder="Nama lengkap"
                           class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                    @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Departemen --}}
                <div>
                    <label for="form-dept" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Departemen <span class="text-red-500">*</span></label>
                    <select id="form-dept" wire:model="departemen_id"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departemens as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                        @endforeach
                    </select>
                    @error('departemen_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Status Aktif --}}
                <div class="flex items-center gap-3 pt-5">
                    <input type="checkbox" id="form-aktif" wire:model="status_aktif"
                           class="w-4 h-4 rounded border-zinc-300 text-blue-600" />
                    <label for="form-aktif" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Karyawan Aktif</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-5">
                <button wire:click="resetForm"
                        class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition">
                    Batal
                </button>
                <button wire:click="simpan" wire:loading.attr="disabled" id="btn-simpan-karyawan"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="simpan"><flux:icon.check variant="outline" class="w-4 h-4" /></span>
                    <span wire:loading wire:target="simpan"><flux:icon.arrow-path class="w-4 h-4 animate-spin" /></span>
                    Simpan
                </button>
            </div>
        </div>
    @endif

    {{-- Search --}}
    <div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari NIK atau Nama..."
               class="w-full max-w-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2" />
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">NIK</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Nama</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Departemen</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Status</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                    @forelse($karyawans as $k)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                            <td class="px-5 py-3 font-mono text-xs text-zinc-700 dark:text-zinc-300">{{ $k->nik }}</td>
                            <td class="px-5 py-3 font-medium text-zinc-900 dark:text-zinc-100">{{ $k->nama }}</td>
                            <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $k->departemen->nama_departemen ?? '-' }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($k->status_aktif)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Non-aktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openEdit('{{ $k->nik }}')" title="Edit"
                                            class="p-1.5 text-zinc-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition">
                                        <flux:icon.pencil variant="outline" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="toggleAktif('{{ $k->nik }}')"
                                            wire:confirm="Ubah status aktif karyawan ini?"
                                            title="{{ $k->status_aktif ? 'Non-aktifkan' : 'Aktifkan' }}"
                                            class="p-1.5 text-zinc-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition">
                                        @if($k->status_aktif)
                                            <flux:icon.x-circle variant="outline" class="w-4 h-4" />
                                        @else
                                            <flux:icon.check-circle variant="outline" class="w-4 h-4" />
                                        @endif
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-zinc-400 dark:text-zinc-500 text-sm">
                                Tidak ada karyawan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($karyawans->hasPages())
            <div class="px-5 py-3 border-t border-zinc-100 dark:border-zinc-700">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>
</div>
