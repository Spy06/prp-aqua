<div class="space-y-5" id="master-akun-user-container">
    @if(session('success'))
        <div
            class="flex items-center gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm">
            <flux:icon.check-circle variant="solid" class="w-4 h-4 shrink-0" />{{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3">
        <h2 class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-zinc-100">Manajemen Akun User</h2>
        <button wire:click="openCreate" id="btn-buat-akun"
            class="self-start xs:self-auto inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
            <flux:icon.user-plus variant="outline" class="w-4 h-4" />
            Buat Akun Baru
        </button>
    </div>

    {{-- Form: Buat Akun Baru --}}
    @if($showFormCreate)
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <flux:icon.user-plus variant="outline" class="w-4 h-4 text-blue-600" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Buat Akun User Baru</h3>
            </div>

            {{-- Aturan penting --}}
            <div
                class="flex items-start gap-2 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 text-amber-700 dark:text-amber-300 text-xs mb-4">
                <flux:icon.information-circle variant="outline" class="w-4 h-4 shrink-0 mt-0.5" />
                <span>Akun hanya bisa dibuat untuk NIK yang <strong>terdaftar dan aktif</strong> di Master Karyawan. Jika
                    NIK tidak ada, tambahkan dulu di tab Master Karyawan.</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- NIK --}}
                <div class="md:col-span-2">
                    <label for="form-nik-baru" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">
                        NIK Karyawan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="form-nik-baru" wire:model.live="nik_baru"
                        placeholder="Masukkan NIK untuk mencari karyawan..."
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                    @error('nik_baru') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                    {{-- Status NIK lookup --}}
                    @if($nikSearchResult)
                        <div
                            class="flex items-center gap-2 mt-2 p-2 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-xs">
                            <flux:icon.check-circle variant="solid" class="w-4 h-4 shrink-0" />
                            Karyawan ditemukan: <strong>{{ $nikSearchResult }}</strong> — siap dibuatkan akun.
                        </div>
                    @elseif($nikSearchError)
                        <div
                            class="flex items-center gap-2 mt-2 p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-xs">
                            <flux:icon.exclamation-triangle variant="solid" class="w-4 h-4 shrink-0" />
                            {{ $nikSearchError }}
                        </div>
                    @endif
                </div>

                {{-- Role --}}
                <div>
                    <label for="form-role" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Role
                        <span class="text-red-500">*</span></label>
                    <select id="form-role" wire:model="role_baru"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="karyawan">Karyawan (dapat melaporkan & tindak lanjut)</option>
                        <option value="qa">QA (akses penuh termasuk verifikasi & master data)</option>
                    </select>
                    @error('role_baru') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- No. WhatsApp --}}
                <div>
                    <label for="form-wa" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">No.
                        WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" id="form-wa" wire:model="no_whatsapp_baru" placeholder="628xxxxxxxxxx"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                    <p class="text-xs text-zinc-400 mt-1">Format: 628xxx (diawali 628 tanpa +)</p>
                    @error('no_whatsapp_baru') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button wire:click="$set('showFormCreate', false)"
                    class="px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400 transition">Batal</button>
                <button wire:click="buatAkun" wire:loading.attr="disabled" id="btn-submit-akun"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition disabled:opacity-50"
                    {{ !$nikSearchResult ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="buatAkun">
                        <flux:icon.check variant="outline" class="w-4 h-4" />
                    </span>
                    <span wire:loading wire:target="buatAkun"><flux:icon.arrow-path class="w-4 h-4 animate-spin" /></span>
                    Buat Akun
                </button>
            </div>
        </div>
    @endif

    {{-- Form: Edit Akun --}}
    @if($showFormEdit)
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5">
            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4">Edit Akun User</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit-role"
                        class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Role</label>
                    <select id="edit-role" wire:model="edit_role"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="karyawan">Karyawan</option>
                        <option value="qa">QA</option>
                    </select>
                    @error('edit_role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="edit-wa" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">No.
                        WhatsApp</label>
                    <input type="text" id="edit-wa" wire:model="edit_no_whatsapp" placeholder="628xxxxxxxxxx"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                    @error('edit_no_whatsapp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button wire:click="$set('showFormEdit', false)"
                    class="px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400 transition">Batal</button>
                <button wire:click="simpanEdit" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition disabled:opacity-50">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    @endif

    {{-- Search --}}
    <div class="w-full md:w-1/2 lg:w-1/3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari NIK atau nama..."
            class="w-full rounded-lg border border-outline-variant bg-surface text-on-surface text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary transition" />
    </div>

    {{-- Table --}}
    <div
        class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[520px]">
                <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">NIK
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Nama
                        </th>
                        <th
                            class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide hidden sm:table-cell">
                            Departemen</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">
                            Role</th>
                        <th
                            class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide hidden sm:table-cell">
                            No. WA</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                    @forelse($users as $u)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                            <td class="px-4 py-3 font-mono text-xs text-zinc-700 dark:text-zinc-300">{{ $u->nik }}</td>
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100 text-xs sm:text-sm">
                                {{ $u->name }}
                                <div class="text-xs text-zinc-500 sm:hidden">
                                    {{ $u->karyawan?->departemen?->nama_departemen ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-xs hidden sm:table-cell">
                                {{ $u->karyawan?->departemen?->nama_departemen ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($u->role === 'qa')
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">QA</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold rounded-full bg-zinc-100 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">Karyawan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-zinc-500 hidden sm:table-cell">
                                {{ $u->no_whatsapp ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="openEdit({{ $u->id }})" title="Edit"
                                    class="p-1.5 text-zinc-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition">
                                    <flux:icon.pencil variant="outline" class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-zinc-400 text-sm">Tidak ada akun user
                                ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-3 border-t border-zinc-100 dark:border-zinc-700">
                {{ $users->links('vendor.pagination.tailwind') }}</div>
        @endif
    </div>
</div>