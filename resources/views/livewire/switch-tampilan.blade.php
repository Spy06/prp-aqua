<div x-data="{ showForm: false }" class="flex flex-col gap-6">
    <!-- Header Dasbor -->
    <section class="flex flex-col gap-1 pt-2 mb-2">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Selamat {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->hour < 11 ? 'Pagi' : (\Carbon\Carbon::now()->timezone('Asia/Jakarta')->hour < 15 ? 'Siang' : (\Carbon\Carbon::now()->timezone('Asia/Jakarta')->hour < 18 ? 'Sore' : 'Malam')) }}, {{ auth()->user()->name }}</h2>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Catat temuan baru untuk menjaga standar area kerja.</p>
    </section>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Tab Pills -->
        <div class="flex items-center p-1.5 bg-slate-100 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-800 shadow-inner">
            <button 
                wire:click="setTab('pelapor')" 
                class="{{ $tab === 'pelapor' ? 'bg-white dark:bg-slate-800 shadow-sm text-cyan-700 dark:text-cyan-400 font-bold' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 font-medium' }} px-5 py-2 text-sm rounded-lg transition-all">
                Pelapor
            </button>
            <button 
                wire:click="setTab('pic')" 
                class="{{ $tab === 'pic' ? 'bg-white dark:bg-slate-800 shadow-sm text-cyan-700 dark:text-cyan-400 font-bold' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 font-medium' }} px-5 py-2 text-sm rounded-lg transition-all flex items-center gap-2">
                PIC
                <!-- Badge: jumlah temuan open/in_progress milik user sebagai PIC -->
                @if($picBadge > 0)
                    <span class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white shadow-sm">{{ $picBadge }}</span>
                @else
                    <span class="rounded-full bg-slate-200 px-2 py-0.5 text-xs font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-400">0</span>
                @endif
            </button>
        </div>

        @if ($tab === 'pelapor')
            <button @click="showForm = !showForm" class="flex items-center gap-2 bg-cyan-50 text-cyan-700 hover:bg-cyan-100 dark:bg-cyan-900/30 dark:text-cyan-400 dark:hover:bg-cyan-900/50 px-4 py-2 rounded-xl font-bold text-sm transition-colors border border-cyan-200 dark:border-cyan-800">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;" x-text="showForm ? 'remove' : 'add'"></span>
                <span x-text="showForm ? 'Tutup Form' : 'Lapor Temuan Baru'"></span>
            </button>
        @endif
    </div>

    <div>
        @if ($tab === 'pelapor')
            <div x-show="showForm" x-collapse x-cloak class="mb-8">
                <livewire:form-temuan />
            </div>
            
            <livewire:daftar-temuan-pelapor />
        @else
            <livewire:daftar-temuan-p-i-c />
        @endif
    </div>
</div>
