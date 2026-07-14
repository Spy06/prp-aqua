<div class="pb-16 md:pb-0">
    {{-- Metric Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
        <!-- Open -->
        <div class="bg-white dark:bg-[#1E3A4A] border border-cyan-100 dark:border-transparent rounded-xl p-4 flex flex-col justify-center shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 w-16 h-16 bg-red-50 dark:bg-red-500/10 rounded-bl-full -mr-4 -mt-4"></div>
            <span class="text-xs font-bold text-zinc-500 dark:text-[#b0bec5] mb-1 relative z-10">Open</span>
            <span class="text-2xl md:text-3xl font-extrabold text-teal-900 dark:text-white leading-none relative z-10">{{ $metrics['open'] ?? 0 }}</span>
        </div>
        <!-- In Progress -->
        <div class="bg-white dark:bg-[#1E3A4A] border border-cyan-100 dark:border-transparent rounded-xl p-4 flex flex-col justify-center shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 w-16 h-16 bg-blue-50 dark:bg-blue-500/10 rounded-bl-full -mr-4 -mt-4"></div>
            <span class="text-xs font-bold text-zinc-500 dark:text-[#b0bec5] mb-1 relative z-10">In Progress</span>
            <span class="text-2xl md:text-3xl font-extrabold text-teal-900 dark:text-white leading-none relative z-10">{{ $metrics['in_progress'] ?? 0 }}</span>
        </div>
        <!-- Pending QA -->
        <div class="bg-white dark:bg-[#1E3A4A] border border-cyan-100 dark:border-transparent rounded-xl p-4 flex flex-col justify-center shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 w-16 h-16 bg-purple-50 dark:bg-purple-500/10 rounded-bl-full -mr-4 -mt-4"></div>
            <span class="text-xs font-bold text-zinc-500 dark:text-[#b0bec5] mb-1 relative z-10">Pending QA</span>
            <span class="text-2xl md:text-3xl font-extrabold text-teal-900 dark:text-white leading-none relative z-10">{{ $metrics['pending_qa'] ?? 0 }}</span>
        </div>
        <!-- Closed -->
        <div class="bg-white dark:bg-[#1E3A4A] border border-cyan-100 dark:border-transparent rounded-xl p-4 flex flex-col justify-center shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 w-16 h-16 bg-teal-50 dark:bg-teal-500/10 rounded-bl-full -mr-4 -mt-4"></div>
            <span class="text-xs font-bold text-zinc-500 dark:text-[#b0bec5] mb-1 relative z-10">Closed</span>
            <span class="text-2xl md:text-3xl font-extrabold text-teal-900 dark:text-white leading-none relative z-10">{{ $metrics['closed'] ?? 0 }}</span>
        </div>
    </div>

    {{-- Title Section --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg md:text-xl font-bold text-zinc-900 dark:text-white">Recent Findings</h2>
            <p class="text-xs md:text-sm text-zinc-500 dark:text-[#b0bec5]">Daftar laporan temuan terakhir Anda</p>
        </div>
        <button class="hidden md:flex text-sm font-bold text-teal-700 dark:text-[#00D4FF] items-center gap-1 hover:text-teal-800 dark:hover:text-[#00a6c7]">
            Sort by Date <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>
    </div>

    {{-- Finding Cards --}}
    @if($temuans->isEmpty())
        <div class="text-center py-12 bg-white dark:bg-[#1E3A4A] rounded-xl border border-dashed border-cyan-200 dark:border-[#2c4a5c]">
            <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-teal-50 dark:bg-[#0B141A] flex items-center justify-center text-teal-600 dark:text-[#00D4FF]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-sm font-bold text-teal-800 dark:text-[#00D4FF]">Belum ada temuan dilaporkan</p>
            <p class="text-xs text-zinc-500 dark:text-[#8ca4b3] mt-1">Gunakan form di atas untuk melapor.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach($temuans as $temuan)
                @php
                    $cardBorderColor = match($temuan->status) {
                        'open' => 'bg-red-600',
                        'in_progress' => 'bg-teal-600',
                        'closed_pending_qa' => 'bg-blue-500',
                        'closed_acc' => 'bg-teal-800',
                        default => 'bg-zinc-400',
                    };

                    $badgeClass = match($temuan->status) {
                        'open' => 'border-red-600 text-red-600',
                        'in_progress' => 'border-teal-600 text-teal-600',
                        'closed_pending_qa' => 'border-blue-500 text-blue-600',
                        'closed_acc' => 'border-teal-800 text-teal-800',
                        default => 'border-zinc-500 text-zinc-600',
                    };

                    $statusText = match($temuan->status) {
                        'open' => 'OPEN',
                        'in_progress' => 'IN PROGRESS',
                        'closed_pending_qa' => 'PENDING QA',
                        'closed_acc' => 'CLOSED',
                        default => 'UNKNOWN',
                    };
                @endphp
                
                <a href="{{ route('temuan.detail', $temuan->id) }}" class="block bg-white dark:bg-[#1E3A4A] rounded-xl shadow-sm border border-zinc-100 dark:border-transparent overflow-hidden relative hover:shadow-md transition group">
                    <!-- Left color border -->
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $cardBorderColor }}"></div>
                    
                    <div class="pl-5 pr-4 py-4 flex flex-col gap-1.5">
                        <div class="flex justify-between items-center mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold border {{ $badgeClass }} uppercase tracking-wider bg-white/50 dark:bg-black/20">
                                {{ $statusText }}
                            </span>
                            <span class="text-[11px] font-bold text-zinc-500 dark:text-[#8ca4b3]">
                                {{ $temuan->tanggal_temuan->isToday() ? 'Today, ' . $temuan->tanggal_temuan->format('H:i') : $temuan->tanggal_temuan->format('M d, H:i') }}
                            </span>
                        </div>
                        
                        <h4 class="text-[15px] font-extrabold text-zinc-900 dark:text-white leading-tight">
                            {{ $temuan->sub_area }}
                        </h4>
                        
                        <p class="text-[13px] font-medium text-zinc-600 dark:text-[#b0bec5] line-clamp-2 leading-snug mb-2">
                            {{ $temuan->deskripsi }}
                        </p>
                        
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-cyan-50 dark:bg-[#0B141A] flex items-center justify-center overflow-hidden shrink-0 border border-cyan-100 dark:border-transparent">
                                @if($temuan->foto_temuan_path)
                                    <img src="{{ Storage::url($temuan->foto_temuan_path) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-teal-600 dark:text-[#00D4FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-zinc-900 dark:text-white">Location: <span class="font-semibold text-zinc-700 dark:text-[#b0bec5]">{{ $temuan->departemen->nama_departemen ?? '-' }}</span></span>
                                <span class="text-[11px] font-medium text-zinc-500 dark:text-[#8ca4b3]">PIC: {{ $temuan->pic->name ?? 'Belum ada PIC' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
    
    <!-- Mobile Floating Action Button -->
    <button @click="showForm = true" x-show="!showForm" class="md:hidden fixed bottom-20 right-4 w-14 h-14 bg-teal-800 dark:bg-[#00D4FF] text-white dark:text-[#0B141A] rounded-2xl shadow-lg flex items-center justify-center hover:bg-teal-900 dark:hover:bg-[#00a6c7] transition-colors z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
    </button>
</div>
