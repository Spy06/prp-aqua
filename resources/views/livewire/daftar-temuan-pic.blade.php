<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Temuan Saya (PIC)</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Temuan yang menunggu tindak lanjut Anda
            </p>
        </div>
        @if($totalAktif > 0)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                {{ $totalAktif }} aktif
            </span>
        @endif
    </div>

    {{-- Daftar Temuan --}}
    @if($temuans->isEmpty())
        <div class="text-center py-16 bg-white dark:bg-zinc-800 rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700">
            <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <flux:icon.check-circle variant="outline" class="w-6 h-6 text-green-600 dark:text-green-400" />
            </div>
            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Tidak ada temuan yang perlu ditindaklanjuti</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">Semua temuan Anda sudah selesai atau belum ada penunjukan PIC.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($temuans as $temuan)
                @php
                    $tl = $temuan->tindakLanjut;
                    $dueDate = $tl?->due_date;

                    // Tentukan urgensi berdasarkan due date
                    $isOverdue  = $dueDate && $dueDate->lt($today) && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);
                    $isDueSoon  = $dueDate && !$isOverdue && $today->diffInDays($dueDate, false) <= 3 && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);

                    $cardBorderClass = match(true) {
                        $isOverdue => 'border-l-4 border-l-red-500',
                        $isDueSoon => 'border-l-4 border-l-yellow-400',
                        default    => '',
                    };

                    $statusBadgeClass = match($temuan->status) {
                        'open'               => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'in_progress'        => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                        'closed_pending_qa'  => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                        'closed_acc'         => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                        default              => 'bg-gray-100 text-gray-800',
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
                   class="block bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 hover:shadow-md transition-all duration-200 overflow-hidden {{ $cardBorderClass }} flex flex-col">

                    {{-- Urgency Banner --}}
                    @if($isOverdue)
                        <div class="bg-red-50 dark:bg-red-900/20 px-4 py-1.5 flex items-center gap-1.5">
                            <flux:icon.exclamation-triangle variant="solid" class="w-3.5 h-3.5 text-red-600 dark:text-red-400" />
                            <span class="text-xs font-semibold text-red-700 dark:text-red-400">
                                OVERDUE — {{ $dueDate->format('d M Y') }}
                            </span>
                        </div>
                    @elseif($isDueSoon)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 px-4 py-1.5 flex items-center gap-1.5">
                            <flux:icon.clock variant="solid" class="w-3.5 h-3.5 text-yellow-600 dark:text-yellow-400" />
                            <span class="text-xs font-semibold text-yellow-700 dark:text-yellow-400">
                                Due {{ $dueDate->diffForHumans() }}
                            </span>
                        </div>
                    @endif

                    <div class="p-5 flex-1 flex flex-col">
                        {{-- Header: dept + status badge --}}
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 leading-snug">
                                {{ $temuan->departemen->nama_departemen ?? '-' }}
                            </h3>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusBadgeClass }} shrink-0 ml-2">
                                {{ $statusText }}
                            </span>
                        </div>

                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                            Sub area: <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $temuan->sub_area }}</span>
                        </p>

                        <p class="text-sm text-zinc-600 dark:text-zinc-300 line-clamp-2 flex-1">
                            {{ $temuan->deskripsi }}
                        </p>

                        @if($tl && $tl->klausul)
                            <div class="mt-2">
                                <span class="inline-block px-2 py-0.5 text-xs bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300 rounded">
                                    {{ $tl->klausul->kode_klausul }}: {{ Str::limit($tl->klausul->nama_klausul, 30) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 py-3 border-t border-zinc-100 dark:border-zinc-700/50 bg-zinc-50 dark:bg-zinc-800/50 flex justify-between items-center text-xs text-zinc-500 dark:text-zinc-400">
                        <div class="flex items-center gap-1.5">
                            <flux:icon.calendar-days variant="outline" class="w-3.5 h-3.5" />
                            {{ $temuan->tanggal_temuan->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-zinc-400">dari:</span>
                            <span class="font-medium text-zinc-600 dark:text-zinc-300 truncate max-w-[90px]">
                                {{ $temuan->pelapor->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
