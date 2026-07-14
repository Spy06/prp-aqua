<div x-data="{ showForm: false }" class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Tab Pills -->
        <div class="flex items-center p-1.5 bg-cyan-50/50 dark:bg-[#0B141A] rounded-xl border border-cyan-100 dark:border-[#2c4a5c] shadow-sm">
            <button 
                wire:click="setTab('pelapor')" 
                class="{{ $tab === 'pelapor' ? 'bg-white dark:bg-[#1E3A4A] shadow-sm text-teal-800 dark:text-[#00D4FF] font-bold border border-cyan-200 dark:border-transparent' : 'text-zinc-500 dark:text-[#8ca4b3] hover:text-teal-700 dark:hover:text-white font-medium' }} px-6 py-2 text-sm rounded-lg transition-all">
                Sebagai Pelapor
            </button>
            <button 
                wire:click="setTab('pic')" 
                class="{{ $tab === 'pic' ? 'bg-white dark:bg-[#1E3A4A] shadow-sm text-teal-800 dark:text-[#00D4FF] font-bold border border-cyan-200 dark:border-transparent' : 'text-zinc-500 dark:text-[#8ca4b3] hover:text-teal-700 dark:hover:text-white font-medium' }} px-6 py-2 text-sm rounded-lg transition-all flex items-center gap-2">
                Sebagai PIC
                <!-- Badge -->
                @if($picBadge > 0)
                    <span class="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-bold text-white shadow-sm">{{ $picBadge }}</span>
                @else
                    <span class="rounded-full bg-zinc-200 dark:bg-[#2c4a5c] px-2 py-0.5 text-[10px] font-bold text-zinc-600 dark:text-[#b0bec5]">0</span>
                @endif
            </button>
        </div>

        @if ($tab === 'pelapor')
            <button @click="showForm = !showForm" class="hidden md:flex items-center gap-2 px-6 py-2.5 bg-teal-800 dark:bg-[#00D4FF] text-white dark:text-[#0B141A] font-bold text-sm rounded-lg hover:bg-teal-900 dark:hover:bg-[#00a6c7] transition-colors shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span x-text="showForm ? 'Tutup Form' : 'Lapor Temuan Baru'"></span>
            </button>
        @endif
    </div>

    <div>
        @if ($tab === 'pelapor')
            <div x-show="showForm" x-collapse x-cloak class="mb-6">
                <!-- Wrapper Form Laporan -->
                <div class="relative">
                    <button @click="showForm = false" class="absolute top-6 right-6 md:top-8 md:right-8 text-zinc-400 hover:text-red-500 transition-colors bg-white dark:bg-[#0B141A] rounded-full p-1 z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <livewire:form-temuan />
                </div>
            </div>
            
            <livewire:daftar-temuan-pelapor />
        @else
            <livewire:daftar-temuan-p-i-c />
        @endif
    </div>
</div>
