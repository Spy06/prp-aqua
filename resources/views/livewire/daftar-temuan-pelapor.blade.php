<div class="space-y-6">
    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Open -->
        <div class="bg-surface p-6 rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3.5 h-3.5 rounded-full bg-[#fbbc04]"></div>
                <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Open</h3>
            </div>
            <p class="font-headline-xl text-headline-xl text-on-surface">{{ $metrics['open'] ?? 0 }}</p>
        </div>

        <!-- In Progress -->
        <div class="bg-surface p-6 rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3.5 h-3.5 rounded-full bg-primary"></div>
                <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">In Progress</h3>
            </div>
            <p class="font-headline-xl text-headline-xl text-on-surface">{{ $metrics['in_progress'] ?? 0 }}</p>
        </div>

        <!-- Pending QA -->
        <div class="bg-surface p-6 rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3.5 h-3.5 rounded-full bg-tertiary"></div>
                <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Pending QA</h3>
            </div>
            <p class="font-headline-xl text-headline-xl text-on-surface">{{ $metrics['pending_qa'] ?? 0 }}</p>
        </div>

        <!-- Closed -->
        <div class="bg-surface p-6 rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3.5 h-3.5 rounded-full bg-[#1e8e3e]"></div>
                <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Closed</h3>
            </div>
            <p class="font-headline-xl text-headline-xl text-on-surface">{{ $metrics['closed'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Title Section -->
    <div class="flex justify-between items-end mt-10 mb-4 px-1">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-background mb-xs">Daftar Temuan</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Laporan temuan yang pernah Anda ajukan</p>
        </div>
    </div>

    <!-- Finding Cards -->
    @if($temuans->isEmpty())
        <div class="text-center py-16 bg-surface text-on-surface-variant border border-dashed border-outline-variant rounded-xl shadow-sm">
            <span class="material-symbols-outlined text-4xl mb-2 text-outline">inbox</span>
            <p class="font-body-md text-body-md">Belum ada temuan yang Anda laporkan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
            @foreach($temuans as $temuan)
                @php
                    $statusBadgeClass = match($temuan->status) {
                        'open' => 'bg-[#fef7e0] text-[#b06000] border-[#fde293]',
                        'in_progress' => 'bg-secondary-container text-on-secondary-container border-outline-variant',
                        'closed_pending_qa' => 'bg-[#fce8e6] text-[#c5221f] border-[#f2b8b5]',
                        'closed_acc' => 'bg-[#e6f4ea] text-[#137333] border-[#ceead6]',
                        default => 'bg-surface-variant text-on-surface-variant border-outline-variant',
                    };
                    $statusText = match($temuan->status) {
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'closed_pending_qa' => 'Pending QA',
                        'closed_acc' => 'Closed (ACC)',
                        default => $temuan->status,
                    };
                @endphp
                
                <a href="{{ route('temuan.detail', $temuan->id) }}" class="block bg-surface rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant hover:shadow-[0px_8px_24px_rgba(0,0,0,0.08)] hover:border-primary transition-all duration-200 overflow-hidden flex flex-col group">
                    <div class="p-md flex-1">
                        <div class="flex justify-between items-start mb-sm">
                            <h3 class="font-title-md text-title-md text-on-surface leading-tight group-hover:text-primary transition-colors">
                                {{ $temuan->departemen->nama_departemen ?? '-' }}
                            </h3>
                            <span class="px-3 py-1 font-label-md text-label-md rounded-full border {{ $statusBadgeClass }} shrink-0 ml-2">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="font-body-sm text-body-sm text-primary mb-sm font-semibold flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                            {{ $temuan->sub_area }}
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">
                            {{ $temuan->deskripsi }}
                        </p>
                    </div>
                    
                    <div class="px-md py-sm border-t border-outline-variant bg-surface-container-low flex justify-between items-center font-label-md text-label-md text-on-surface-variant">
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

        <div class="mt-lg">
            {{ $temuans->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
