<div class="space-y-6">
    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Open -->
        <div class="bg-white dark:bg-slate-900/80 p-6 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700/50 flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3.5 h-3.5 rounded-full bg-yellow-400 shadow-sm shadow-yellow-400/50"></div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Open</h3>
            </div>
            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $metrics['open'] ?? 0 }}</p>
        </div>

        <!-- In Progress -->
        <div class="bg-white dark:bg-slate-900/80 p-6 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700/50 flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3.5 h-3.5 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">In Progress</h3>
            </div>
            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $metrics['in_progress'] ?? 0 }}</p>
        </div>

        <!-- Pending QA -->
        <div class="bg-white dark:bg-slate-900/80 p-6 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700/50 flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3.5 h-3.5 rounded-full bg-purple-500 shadow-sm shadow-purple-500/50"></div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pending QA</h3>
            </div>
            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $metrics['pending_qa'] ?? 0 }}</p>
        </div>

        <!-- Closed -->
        <div class="bg-white dark:bg-slate-900/80 p-6 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700/50 flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3.5 h-3.5 rounded-full bg-green-500 shadow-sm shadow-green-500/50"></div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Closed</h3>
            </div>
            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $metrics['closed'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Title Section -->
    <div class="flex justify-between items-end mt-10 mb-4 px-1">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Daftar Temuan</h2>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-0.5">Laporan temuan yang pernah Anda ajukan</p>
        </div>
    </div>

    <!-- Finding Cards -->
    @if($temuans->isEmpty())
        <div class="text-center py-16 bg-white dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 border border-dashed border-slate-300 dark:border-slate-700 rounded-2xl shadow-sm">
            <span class="material-symbols-outlined text-4xl mb-2 text-slate-400 dark:text-slate-600">inbox</span>
            <p class="font-medium">Belum ada temuan yang Anda laporkan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($temuans as $temuan)
                @php
                    $statusBadgeClass = match($temuan->status) {
                        'open' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500 border-yellow-200 dark:border-yellow-800/50',
                        'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500 border-blue-200 dark:border-blue-800/50',
                        'closed_pending_qa' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-500 border-purple-200 dark:border-purple-800/50',
                        'closed_acc' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500 border-green-200 dark:border-green-800/50',
                        default => 'bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-500 border-slate-200 dark:border-slate-800/50',
                    };
                    $statusText = match($temuan->status) {
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'closed_pending_qa' => 'Pending QA',
                        'closed_acc' => 'Closed (ACC)',
                        default => $temuan->status,
                    };
                @endphp
                
                <a href="{{ route('temuan.detail', $temuan->id) }}" class="block bg-white dark:bg-slate-900/80 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700/50 hover:shadow-xl hover:border-cyan-300 dark:hover:border-cyan-700 transition-all duration-200 overflow-hidden flex flex-col group">
                    <div class="p-6 flex-1">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 leading-tight group-hover:text-cyan-700 dark:group-hover:text-cyan-400 transition-colors">
                                {{ $temuan->departemen->nama_departemen ?? '-' }}
                            </h3>
                            <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $statusBadgeClass }} shrink-0 ml-2">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="text-sm text-cyan-700 dark:text-cyan-500 mb-3 font-semibold flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                            {{ $temuan->sub_area }}
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-2 font-medium">
                            {{ $temuan->deskripsi }}
                        </p>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center text-xs font-medium text-slate-500 dark:text-slate-400">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            {{ $temuan->tanggal_temuan->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">person</span>
                            <span class="truncate max-w-[120px]">{{ $temuan->pic->name ?? 'Belum ada PIC' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
