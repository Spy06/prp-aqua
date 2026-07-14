<div class="pb-16 md:pb-0">
    {{-- Header & Icons --}}
    <div class="flex items-center justify-between mb-4 pt-2">
        <h2 class="text-2xl font-bold text-teal-900 dark:text-white">Active Findings</h2>
        <div class="flex gap-2">
            <button class="w-10 h-10 flex items-center justify-center bg-cyan-50/80 dark:bg-[#0B141A] border border-transparent dark:border-[#2c4a5c] text-teal-800 dark:text-[#00D4FF] rounded-full hover:bg-cyan-100 dark:hover:bg-[#13242e] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </button>
            <button class="w-10 h-10 flex items-center justify-center bg-cyan-50/80 dark:bg-[#0B141A] border border-transparent dark:border-[#2c4a5c] text-teal-800 dark:text-[#00D4FF] rounded-full hover:bg-cyan-100 dark:hover:bg-[#13242e] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="grid grid-cols-3 gap-3 mb-8">
        <!-- Urgent / Open -->
        <div class="bg-red-100/70 dark:bg-[#1E3A4A] border border-red-200 dark:border-red-900/50 rounded-xl p-3 flex flex-col justify-center">
            <span class="text-2xl font-bold text-red-800 dark:text-red-400 leading-none">{{ $metrics['open'] ?? 0 }}</span>
            <span class="text-xs font-bold text-red-700 dark:text-red-500 mt-1">Urgent</span>
        </div>
        <!-- Pending / In Progress -->
        <div class="bg-blue-50 dark:bg-[#1E3A4A] border border-blue-100 dark:border-blue-900/50 rounded-xl p-3 flex flex-col justify-center">
            <span class="text-2xl font-bold text-slate-800 dark:text-blue-400 leading-none">{{ ($metrics['in_progress'] ?? 0) + ($metrics['pending_qa'] ?? 0) }}</span>
            <span class="text-xs font-bold text-slate-600 dark:text-blue-500 mt-1">Pending</span>
        </div>
        <!-- Resolved / Closed -->
        <div class="bg-teal-100/60 dark:bg-[#1E3A4A] border border-teal-200 dark:border-teal-900/50 rounded-xl p-3 flex flex-col justify-center">
            <span class="text-2xl font-bold text-teal-900 dark:text-[#00D4FF] leading-none">{{ $metrics['closed'] ?? 0 }}</span>
            <span class="text-xs font-bold text-teal-700 dark:text-[#00a6c7] mt-1">Resolved</span>
        </div>
    </div>

    {{-- Recent Reports Header --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Recent Reports</h3>
        <button class="text-sm font-bold text-teal-700 dark:text-[#00D4FF] flex items-center gap-1 hover:text-teal-800 dark:hover:text-[#00a6c7]">
            Sort by Date 
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>
    </div>

    {{-- Daftar Temuan --}}
    @if($temuans->isEmpty())
        <div class="text-center py-12 bg-white dark:bg-[#1E3A4A] rounded-xl border border-dashed border-cyan-200 dark:border-[#2c4a5c]">
            <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-teal-50 dark:bg-[#0B141A] flex items-center justify-center text-teal-600 dark:text-[#00D4FF]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-sm font-bold text-teal-800 dark:text-[#00D4FF]">Tidak ada active findings</p>
            <p class="text-xs text-zinc-500 dark:text-[#8ca4b3] mt-1">Semua temuan Anda sudah diselesaikan.</p>
        </div>
    @else
        <div class="flex flex-col gap-4">
            @foreach($temuans as $temuan)
                @php
                    $tl = $temuan->tindakLanjut;
                    $dueDate = $tl?->due_date;
                    $isOverdue = $dueDate && $dueDate->lt($today) && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);

                    // Colors based on status
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
                        'open' => 'ACTION REQUIRED',
                        'in_progress' => 'IN PROGRESS',
                        'closed_pending_qa' => 'PENDING QA',
                        'closed_acc' => 'RESOLVED',
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
                                <span class="text-[11px] font-medium text-zinc-500 dark:text-[#8ca4b3]">Reported by: {{ $temuan->pelapor->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <!-- Mobile Floating Action Button -->
    <a href="{{ route('beranda') }}" class="md:hidden fixed bottom-20 right-4 w-14 h-14 bg-teal-800 dark:bg-[#00D4FF] text-white dark:text-[#0B141A] rounded-2xl shadow-lg flex items-center justify-center hover:bg-teal-900 dark:hover:bg-[#00a6c7] transition-colors z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
    </a>
</div>
