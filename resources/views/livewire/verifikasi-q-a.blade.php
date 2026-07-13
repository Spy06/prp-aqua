<div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-amber-200 dark:border-amber-700/50 overflow-hidden mt-6">
    <div class="px-6 py-4 border-b border-amber-100 dark:border-amber-900/30 flex items-center gap-3 bg-amber-50/50 dark:bg-amber-900/10">
        <div class="w-9 h-9 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center">
            <flux:icon.shield-check variant="outline" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
        </div>
        <div>
            <h2 class="text-base font-semibold text-amber-900 dark:text-amber-100">Verifikasi QA</h2>
            <p class="text-xs text-amber-700 dark:text-amber-300">Tinjau tindak lanjut PIC dan berikan keputusan</p>
        </div>
    </div>

    <div class="p-6">
        <form wire:submit.prevent="tolak" class="space-y-4">
            <div>
                <label for="catatan_qa" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Catatan QA (Wajib diisi jika menolak)</label>
                <textarea 
                    wire:model="catatan_qa" 
                    id="catatan_qa" 
                    rows="3" 
                    class="block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                    placeholder="Tulis alasan jika bukti kurang jelas atau tindakan tidak sesuai..."
                ></textarea>
                @error('catatan_qa') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button 
                    type="button" 
                    wire:click="setujui" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    <flux:icon.check class="w-4 h-4 mr-2" /> Setujui (Closed)
                </button>

                <button 
                    type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    <flux:icon.x-mark class="w-4 h-4 mr-2" /> Tolak (Kembali ke In Progress)
                </button>
            </div>
        </form>
    </div>
</div>
