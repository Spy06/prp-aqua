<div class="space-y-6" id="tindak-lanjut-pic-form">

    {{-- Flash Messages --}}
    @if(session('status_success'))
        <div class="flex items-center gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm">
            <flux:icon.check-circle variant="solid" class="w-4 h-4 shrink-0" />
            {{ session('status_success') }}
        </div>
    @endif

    @if(session('status_error'))
        <div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 text-sm">
            <flux:icon.exclamation-triangle variant="solid" class="w-4 h-4 shrink-0" />
            {{ session('status_error') }}
        </div>
    @endif

    @if(session('detail_success'))
        <div class="flex items-center gap-2 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300 text-sm">
            <flux:icon.check-circle variant="solid" class="w-4 h-4 shrink-0" />
            {{ session('detail_success') }}
        </div>
    @endif

    @if(session('foto_success'))
        <div class="flex items-center gap-2 p-3 rounded-lg bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-700 text-teal-700 dark:text-teal-300 text-sm">
            <flux:icon.photo variant="solid" class="w-4 h-4 shrink-0" />
            {{ session('foto_success') }}
        </div>
    @endif

    {{-- Status saat ini --}}
    @php
        $statusBadgeClass = match($currentStatus) {
            'open'              => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'in_progress'       => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'closed_pending_qa' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'closed_acc'        => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            default             => 'bg-gray-100 text-gray-800',
        };
        $statusText = match($currentStatus) {
            'open'              => 'Open',
            'in_progress'       => 'In Progress',
            'closed_pending_qa' => 'Closed Pending QA (Menunggu Verifikasi QA)',
            'closed_acc'        => 'Closed — Disetujui QA',
            default             => $currentStatus,
        };
    @endphp

    <div class="flex items-center gap-3">
        <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Status tindak lanjut:</span>
        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusBadgeClass }}">{{ $statusText }}</span>
    </div>

    {{-- Section 1: Detail Tindak Lanjut --}}
    <div class="space-y-4">
        <h4 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wide border-b border-zinc-100 dark:border-zinc-700 pb-2">
            Detail Tindak Lanjut
        </h4>

        {{-- Tindakan / Action --}}
        <div>
            <label for="action" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                Tindakan Perbaikan <span class="text-red-500">*</span>
            </label>
            <textarea id="action"
                      wire:model="action"
                      rows="4"
                      placeholder="Jelaskan tindakan perbaikan yang dilakukan atau direncanakan..."
                      class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                      {{ $currentStatus === 'closed_pending_qa' || $currentStatus === 'closed_acc' ? 'disabled' : '' }}></textarea>
            @error('action')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Due Date --}}
        <div>
            <label for="due_date" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                Due Date <span class="text-red-500">*</span>
            </label>
            <input type="date"
                   id="due_date"
                   wire:model="due_date"
                   class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                   {{ $currentStatus === 'closed_pending_qa' || $currentStatus === 'closed_acc' ? 'disabled' : '' }} />
            @error('due_date')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        @if(!in_array($currentStatus, ['closed_pending_qa', 'closed_acc']))
            <div class="flex justify-end">
                <button wire:click="simpanDetail"
                        wire:loading.attr="disabled"
                        id="btn-simpan-detail"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="simpanDetail">
                        <flux:icon.check variant="outline" class="w-4 h-4" />
                    </span>
                    <span wire:loading wire:target="simpanDetail">
                        <flux:icon.arrow-path class="w-4 h-4 animate-spin" />
                    </span>
                    Simpan Detail
                </button>
            </div>
        @endif
    </div>

    {{-- Section 2: Foto Bukti --}}
    <div class="space-y-4 pt-4 border-t border-zinc-100 dark:border-zinc-700">
        <h4 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wide pb-1">
            Foto Bukti
            @if(empty($foto_bukti_path))
                <span class="ml-1 text-xs font-normal text-orange-500 normal-case">(Wajib sebelum closed)</span>
            @else
                <span class="ml-1 text-xs font-normal text-green-600 dark:text-green-400 normal-case">✓ Sudah diupload</span>
            @endif
        </h4>

        {{-- Tampilkan foto yang sudah ada --}}
        @if($foto_bukti_path)
            <div class="rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                <img src="{{ Storage::disk('public')->url($foto_bukti_path) }}"
                     alt="Foto bukti tindak lanjut"
                     class="w-full max-h-64 object-contain bg-zinc-50 dark:bg-zinc-900" />
            </div>
        @endif

        {{-- Upload foto baru (hanya jika belum closed_acc) --}}
        @if($currentStatus !== 'closed_acc')
            <div class="space-y-2">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ $foto_bukti_path ? 'Ganti Foto Bukti' : 'Upload Foto Bukti' }}
                    <span class="text-xs font-normal text-zinc-500 ml-1">(JPG/PNG, maks. 5MB)</span>
                </label>

                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <input type="file"
                               id="foto-bukti-input"
                               wire:model="foto_bukti"
                               accept="image/*"
                               class="block w-full text-sm text-zinc-500 dark:text-zinc-400
                                      file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      dark:file:bg-blue-900/30 dark:file:text-blue-300
                                      hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50
                                      transition cursor-pointer" />

                        @error('foto_bukti')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Preview sebelum upload --}}
                        @if($foto_bukti)
                            <div class="mt-2 rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                                <img src="{{ $foto_bukti->temporaryUrl() }}"
                                     alt="Preview foto bukti"
                                     class="w-full max-h-40 object-contain bg-zinc-50 dark:bg-zinc-900" />
                            </div>
                        @endif
                    </div>

                    <button wire:click="uploadFoto"
                            wire:loading.attr="disabled"
                            id="btn-upload-foto"
                            class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition disabled:opacity-50">
                        <span wire:loading.remove wire:target="uploadFoto">
                            <flux:icon.arrow-up-tray variant="outline" class="w-4 h-4" />
                        </span>
                        <span wire:loading wire:target="uploadFoto">
                            <flux:icon.arrow-path class="w-4 h-4 animate-spin" />
                        </span>
                        Upload
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Section 3: Ubah Status --}}
    @if(!in_array($currentStatus, ['closed_acc']))
        <div class="space-y-3 pt-4 border-t border-zinc-100 dark:border-zinc-700">
            <h4 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wide pb-1">
                Ubah Status
            </h4>

            {{-- Petunjuk status flow --}}
            <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400 mb-2">
                <span class="px-2 py-0.5 rounded {{ $currentStatus === 'open' ? 'bg-yellow-100 text-yellow-800 font-semibold' : 'bg-zinc-100 dark:bg-zinc-700' }}">Open</span>
                <flux:icon.arrow-right class="w-3 h-3" />
                <span class="px-2 py-0.5 rounded {{ $currentStatus === 'in_progress' ? 'bg-blue-100 text-blue-800 font-semibold' : 'bg-zinc-100 dark:bg-zinc-700' }}">In Progress</span>
                <flux:icon.arrow-right class="w-3 h-3" />
                <span class="px-2 py-0.5 rounded {{ $currentStatus === 'closed_pending_qa' ? 'bg-purple-100 text-purple-800 font-semibold' : 'bg-zinc-100 dark:bg-zinc-700' }}">Pending QA</span>
                <flux:icon.arrow-right class="w-3 h-3" />
                <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-400">ACC (QA)</span>
            </div>

            {{-- Tombol transisi status --}}
            <div class="flex flex-wrap gap-3">
                @if($currentStatus === 'open')
                    {{-- open → in_progress --}}
                    <button wire:click="ubahStatus('in_progress')"
                            wire:loading.attr="disabled"
                            wire:confirm="Ubah status ke In Progress?"
                            id="btn-status-in-progress"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition disabled:opacity-50">
                        <flux:icon.play variant="solid" class="w-4 h-4" />
                        Mulai Pengerjaan (In Progress)
                    </button>
                @elseif($currentStatus === 'in_progress')
                    {{-- in_progress → closed_pending_qa (WAJIB ada foto bukti) --}}
                    <div class="w-full">
                        @if(empty($foto_bukti_path))
                            <div class="p-3 rounded-lg bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 text-orange-700 dark:text-orange-300 text-xs mb-3">
                                <strong>⚠ Foto bukti wajib diupload</strong> sebelum Anda bisa menutup laporan untuk verifikasi QA.
                            </div>
                        @endif

                        <button wire:click="ubahStatus('closed_pending_qa')"
                                wire:loading.attr="disabled"
                                wire:confirm="Selesaikan dan kirim ke QA untuk verifikasi? Pastikan foto bukti sudah diupload."
                                id="btn-status-pending-qa"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition disabled:opacity-50"
                                {{ empty($foto_bukti_path) ? 'disabled' : '' }}>
                            <flux:icon.paper-airplane variant="solid" class="w-4 h-4" />
                            Selesai & Kirim ke QA
                        </button>

                        @if(empty($foto_bukti_path))
                            <p class="text-xs text-orange-500 mt-1">Upload foto bukti terlebih dahulu untuk mengaktifkan tombol ini.</p>
                        @endif
                    </div>
                @elseif($currentStatus === 'closed_pending_qa')
                    <div class="flex items-center gap-2 p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 text-purple-700 dark:text-purple-300 text-sm">
                        <flux:icon.clock variant="outline" class="w-4 h-4 shrink-0" />
                        <span>Menunggu verifikasi QA. Anda tidak dapat mengubah status lebih lanjut.</span>
                    </div>
                @endif
            </div>

            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">
                <flux:icon.lock-closed variant="outline" class="w-3 h-3 inline" />
                Status <em>Closed ACC</em> hanya bisa diset oleh QA setelah verifikasi.
            </p>
        </div>
    @endif
</div>
