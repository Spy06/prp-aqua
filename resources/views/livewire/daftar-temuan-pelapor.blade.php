<div class="space-y-6">
    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Open -->
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Open</h3>
            </div>
            <p class="text-3xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $metrics['open'] ?? 0 }}</p>
        </div>

        <!-- In Progress -->
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">In Progress</h3>
            </div>
            <p class="text-3xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $metrics['in_progress'] ?? 0 }}</p>
        </div>

        <!-- Pending QA -->
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Pending QA</h3>
            </div>
            <p class="text-3xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $metrics['pending_qa'] ?? 0 }}</p>
        </div>

        <!-- Closed -->
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 flex flex-col justify-between">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Closed</h3>
            </div>
            <p class="text-3xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $metrics['closed'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Title Section -->
    <div class="flex justify-between items-end mt-8 mb-4">
        <div>
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Recent Findings</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Daftar laporan temuan terakhir Anda</p>
        </div>
    </div>

    <!-- Finding Cards -->
    @if($temuans->isEmpty())
        <div class="text-center py-12 bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 border border-dashed border-zinc-300 dark:border-zinc-700 rounded-xl shadow-sm">
            Belum ada temuan yang Anda laporkan.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($temuans as $temuan)
                @php
                    $statusBadgeClass = match($temuan->status) {
                        'open' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500',
                        'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500',
                        'closed_pending_qa' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-500',
                        'closed_acc' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500',
                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-500',
                    };
                    $statusText = match($temuan->status) {
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'closed_pending_qa' => 'Pending QA',
                        'closed_acc' => 'Closed (ACC)',
                        default => $temuan->status,
                    };
                @endphp
                
                <a href="{{ route('temuan.detail', $temuan->id) }}" class="block bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col">
                    <div class="p-5 flex-1">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 leading-tight">
                                {{ $temuan->departemen->nama_departemen ?? '-' }}
                            </h3>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadgeClass }} shrink-0 ml-2">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400 mb-2 font-medium">
                            Sub: {{ $temuan->sub_area }}
                        </div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-300 line-clamp-2">
                            {{ $temuan->deskripsi }}
                        </p>
                    </div>
                    
                    <div class="px-5 py-3 border-t border-zinc-100 dark:border-zinc-700/50 bg-zinc-50 dark:bg-zinc-800/50 flex justify-between items-center text-xs text-zinc-500 dark:text-zinc-400">
                        <div class="flex items-center gap-1.5">
                            <flux:icon.clock variant="outline" class="w-4 h-4" />
                            {{ $temuan->tanggal_temuan->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <flux:icon.user variant="outline" class="w-4 h-4" />
                            <span class="truncate max-w-[100px]">{{ $temuan->pic->name ?? 'Belum ada PIC' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
