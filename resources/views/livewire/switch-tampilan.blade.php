<div x-data="{ showForm: false }" class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Tab Pills -->
        <div class="flex items-center p-1 bg-zinc-100 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-700/50">
            <button 
                wire:click="setTab('pelapor')" 
                class="{{ $tab === 'pelapor' ? 'bg-white dark:bg-zinc-700 shadow-sm text-zinc-900 dark:text-zinc-100' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800' }} px-4 py-2 text-sm font-medium rounded-md transition-colors">
                Pelapor
            </button>
            <button 
                wire:click="setTab('pic')" 
                class="{{ $tab === 'pic' ? 'bg-white dark:bg-zinc-700 shadow-sm text-zinc-900 dark:text-zinc-100' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800' }} px-4 py-2 text-sm font-medium rounded-md transition-colors flex items-center gap-2">
                PIC
                <!-- Placeholder badge untuk PIC -->
                <span class="rounded-full bg-zinc-200 px-2 py-0.5 text-xs font-semibold text-zinc-600 dark:bg-zinc-600 dark:text-zinc-300">0</span>
            </button>
        </div>

        @if ($tab === 'pelapor')
            <flux:button variant="primary" icon="plus" @click="showForm = !showForm">
                Lapor Temuan Baru
            </flux:button>
        @endif
    </div>

    <div>
        @if ($tab === 'pelapor')
            <div x-show="showForm" x-collapse x-cloak class="mb-6">
                <div class="bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700 rounded-xl p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-zinc-100 dark:border-zinc-700 pb-4">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Form Laporan Temuan</h2>
                        <flux:button variant="subtle" size="sm" icon="x-mark" @click="showForm = false" />
                    </div>
                    <livewire:form-temuan />
                </div>
            </div>
            
            <livewire:daftar-temuan-pelapor />
        @else
            <div class="p-6 bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700 rounded-xl">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Daftar Tindak Lanjut (PIC)</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Fitur ini akan diimplementasikan mendatang.</p>
            </div>
        @endif
    </div>
</div>
