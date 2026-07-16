<div class="space-y-5" id="master-departemen-container">
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

    <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3">
        <h2 class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-zinc-100">Master Departemen</h2>
        <button wire:click="openCreate" id="btn-tambah-dept"
                class="self-start xs:self-auto inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
            <flux:icon.plus variant="outline" class="w-4 h-4" />
            Tambah Departemen
        </button>
    </div>

    @if($showForm)
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5">
            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4">
                {{ $editingId ? 'Edit Departemen' : 'Tambah Departemen Baru' }}
            </h3>
            <div class="max-w-md">
                <label for="form-dept-nama" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Nama Departemen <span class="text-red-500">*</span></label>
                <input type="text" id="form-dept-nama" wire:model="nama_departemen" placeholder="Contoh: Quality Assurance"
                       class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                @error('nama_departemen') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button wire:click="resetForm"
                        class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition">
                    Batal
                </button>
                <button wire:click="simpan" wire:loading.attr="disabled" id="btn-simpan-dept"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="simpan"><flux:icon.check variant="outline" class="w-4 h-4" /></span>
                    <span wire:loading wire:target="simpan"><flux:icon.arrow-path class="w-4 h-4 animate-spin" /></span>
                    Simpan
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase">Nama Departemen</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase">Karyawan</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase">Temuan</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                    @forelse($departemens as $d)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100 text-sm">{{ $d->nama_departemen }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">{{ $d->karyawans_count }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">{{ $d->temuans_count }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openEdit({{ $d->id }})" title="Edit"
                                            class="p-1.5 text-zinc-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition">
                                        <flux:icon.pencil variant="outline" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="hapus({{ $d->id }})"
                                            wire:confirm="Yakin hapus departemen '{{ $d->nama_departemen }}'?"
                                            title="Hapus"
                                            class="p-1.5 text-zinc-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                                        <flux:icon.trash variant="outline" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-zinc-400 dark:text-zinc-500 text-sm">Belum ada departemen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($departemens->hasPages())
            <div class="px-5 py-3 border-t border-zinc-100 dark:border-zinc-700">
                {{ $departemens->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>
