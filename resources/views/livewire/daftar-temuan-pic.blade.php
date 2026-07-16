<div class="space-y-6">
    {{-- Metric Cards --}}
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

    {{-- Header --}}
    <div class="flex justify-between items-end mt-10 mb-4 px-1">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-background mb-xs">Temuan Saya (PIC)</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Temuan yang menunggu tindak lanjut Anda</p>
        </div>
    </div>

    {{-- Daftar Temuan --}}
    @if($temuans->isEmpty())
        <div class="text-center py-16 bg-surface rounded-xl border border-dashed border-outline-variant shadow-sm">
            <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-[#e6f4ea] flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px] text-[#137333]">check_circle</span>
            </div>
            <p class="font-body-md text-body-md text-on-surface">Tidak ada temuan yang perlu ditindaklanjuti</p>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Semua temuan Anda sudah selesai atau belum ada penunjukan PIC.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
            @foreach($temuans as $temuan)
                @php
                    $tl = $temuan->tindakLanjut;
                    $dueDate = $tl?->due_date;

                    // Tentukan urgensi berdasarkan due date
                    $isOverdue  = $dueDate && $dueDate->lt($today) && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);
                    $isDueSoon  = $dueDate && !$isOverdue && $today->diffInDays($dueDate, false) <= 3 && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);
                    $isPendingQa = $temuan->status === 'closed_pending_qa';

                    $cardBorderClass = match(true) {
                        $isOverdue => 'border-l-4 border-l-error',
                        $isDueSoon => 'border-l-4 border-l-[#fbbc04]',
                        $isPendingQa => 'border-l-4 border-l-tertiary',
                        default    => '',
                    };

                    $statusBadgeClass = match($temuan->status) {
                        'open'               => 'bg-[#fef7e0] text-[#b06000] border-[#fde293]',
                        'in_progress'        => 'bg-secondary-container text-on-secondary-container border-outline-variant',
                        'closed_pending_qa'  => 'bg-[#fce8e6] text-[#c5221f] border-[#f2b8b5]',
                        'closed_acc'         => 'bg-[#e6f4ea] text-[#137333] border-[#ceead6]',
                        default              => 'bg-surface-variant text-on-surface-variant border-outline-variant',
                    };

                    $statusText = match($temuan->status) {
                        'open'               => 'Open',
                        'in_progress'        => 'In Progress',
                        'closed_pending_qa'  => 'Pending QA',
                        'closed_acc'         => 'Closed (ACC)',
                        default              => $temuan->status,
                    };
                @endphp

                <a href="{{ route('temuan.detail', $temuan->id) }}"
                   class="block bg-surface rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant hover:shadow-[0px_8px_24px_rgba(0,0,0,0.08)] transition-all duration-200 overflow-hidden {{ $cardBorderClass }} flex flex-col group">

                    {{-- Urgency Banner --}}
                    @if($isOverdue)
                        <div class="bg-error-container text-on-error-container px-4 py-2 flex items-center gap-1.5 border-b border-error">
                            <span class="material-symbols-outlined text-[16px] filled-icon">warning</span>
                            <span class="font-label-md text-label-md">
                                OVERDUE — {{ $dueDate->format('d M Y') }}
                            </span>
                        </div>
                    @elseif($isDueSoon)
                        <div class="bg-[#fef7e0] text-[#b06000] px-4 py-2 flex items-center gap-1.5 border-b border-[#fde293]">
                            <span class="material-symbols-outlined text-[16px] filled-icon">schedule</span>
                            <span class="font-label-md text-label-md">
                                Due {{ $dueDate->diffForHumans() }}
                            </span>
                        </div>
                    @elseif($isPendingQa)
                        <div class="bg-[#f3e8fd] text-[#6b1cb0] px-4 py-2 flex items-center gap-1.5 border-b border-[#e9d2fc]">
                            <span class="material-symbols-outlined text-[16px] filled-icon">shield</span>
                            <span class="font-label-md text-label-md">
                                Sedang ditinjau QA
                            </span>
                        </div>
                    @endif

                    <div class="p-md flex-1 flex flex-col">
                        {{-- Header: dept + status badge --}}
                        <div class="flex justify-between items-start mb-sm">
                            <h3 class="font-title-md text-title-md text-on-surface leading-snug group-hover:text-primary transition-colors">
                                {{ $temuan->departemen->nama_departemen ?? '-' }}
                            </h3>
                            <span class="px-3 py-1 font-label-md text-label-md rounded-full border {{ $statusBadgeClass }} shrink-0 ml-2">
                                {{ $statusText }}
                            </span>
                        </div>

                        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">
                            Sub area: <span class="font-semibold text-primary">{{ $temuan->sub_area }}</span>
                        </p>

                        <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 flex-1">
                            {{ $temuan->deskripsi }}
                        </p>

                        @if($temuan->klausul)
                            <div class="mt-sm">
                                <span class="inline-block px-2 py-1 font-label-md text-[10px] bg-surface-container-high text-on-surface rounded border border-outline-variant/50">
                                    {{ $temuan->klausul->kode_klausul }}: {{ Str::limit($temuan->klausul->nama_klausul, 30) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="px-md py-sm border-t border-outline-variant bg-surface-container-low flex justify-between items-center font-label-md text-label-md text-on-surface-variant">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            {{ $temuan->tanggal_temuan->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-1">
                            <span>dari:</span>
                            <span class="font-semibold text-on-surface truncate max-w-[90px]">
                                {{ $temuan->pelapor->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-lg">
            {{ $temuans->links() }}
        </div>
    @endif
</div>
