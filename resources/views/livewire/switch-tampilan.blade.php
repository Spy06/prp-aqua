<div class="flex flex-col gap-6">
    <div class="border-b border-zinc-200 dark:border-zinc-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button 
                wire:click="setTab('pelapor')" 
                class="{{ $tab === 'pelapor' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                Tampilan Pelapor
            </button>
            <button 
                wire:click="setTab('pic')" 
                class="{{ $tab === 'pic' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                Tampilan PIC
                <!-- Placeholder badge untuk PIC -->
                <span class="ml-2 rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">0</span>
            </button>
        </nav>
    </div>

    <div class="p-4 bg-white dark:bg-zinc-800 shadow rounded-lg">
        @if ($tab === 'pelapor')
            <livewire:form-temuan />
            <livewire:daftar-temuan-pelapor />
        @else
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Daftar Tindak Lanjut (PIC)</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Fitur ini akan diimplementasikan mendatang.</p>
        @endif
    </div>
</div>
