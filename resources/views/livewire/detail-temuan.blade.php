<div class="max-w-4xl mx-auto space-y-6" id="detail-temuan-container">
    {{-- Header + Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
        <a href="{{ route('beranda') }}" class="hover:text-zinc-700 dark:hover:text-zinc-200 transition">Beranda</a>
        <flux:icon.chevron-right class="w-4 h-4" />
        <span class="text-zinc-900 dark:text-zinc-100 font-medium">Temuan #{{ $temuan->id }}</span>
    </div>

    {{-- Card: Info Temuan --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-700 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <flux:icon.document-text variant="outline" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Detail Temuan PRP #{{ $temuan->id }}</h1>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Dilaporkan {{ $temuan->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Status Badge --}}
            @php
                $statusBadgeClass = match($temuan->status) {
                    'open'              => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'in_progress'       => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                    'closed_pending_qa' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                    'closed_acc'        => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                    default             => 'bg-gray-100 text-gray-800',
                };
                $statusText = match($temuan->status) {
                    'open'              => 'Open',
                    'in_progress'       => 'In Progress',
                    'closed_pending_qa' => 'Pending QA',
                    'closed_acc'        => 'Closed (ACC)',
                    default             => $temuan->status,
                };
            @endphp
            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusBadgeClass }}">
                {{ $statusText }}
            </span>
        </div>

        {{-- Card Body: Info Grid --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Kolom Kiri --}}
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Tanggal Temuan</p>
                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $temuan->tanggal_temuan->format('d F Y') }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Departemen</p>
                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $temuan->departemen->nama_departemen ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Sub Area</p>
                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $temuan->sub_area }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Klausul PRP</p>
                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $temuan->klausul ? $temuan->klausul->kode_klausul . ' — ' . $temuan->klausul->nama_klausul : '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Pelapor</p>
                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $temuan->pelapor->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">PIC yang Ditunjuk</p>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold">
                            {{ substr($temuan->pic->name ?? '?', 0, 1) }}
                        </div>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $temuan->pic->name ?? '-' }}</p>
                        @if($isPic)
                            <span class="px-1.5 py-0.5 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded">Anda</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Deskripsi + Foto --}}
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Deskripsi Temuan</p>
                    <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed whitespace-pre-wrap">{{ $temuan->deskripsi }}</p>
                </div>

                @if($temuan->foto_temuan_path)
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-2">Foto Temuan</p>
                        <div class="rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                            <img src="{{ Storage::disk('public')->url($temuan->foto_temuan_path) }}"
                                 alt="Foto temuan PRP"
                                 class="w-full max-h-72 object-cover" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Card: Tindak Lanjut (info ringkas, selalu ditampilkan) --}}
    @php $tl = $temuan->tindakLanjut; @endphp
    @if($tl)
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center">
                    <flux:icon.clipboard-document-check variant="outline" class="w-5 h-5 text-teal-600 dark:text-teal-400" />
                </div>
                <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Tindak Lanjut</h2>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Tindakan Perbaikan</p>
                        @if($tl->action)
                            <p class="text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap">{{ $tl->action }}</p>
                        @else
                            <p class="text-sm text-zinc-400 italic">Belum diisi</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Due Date</p>
                        @if($tl->due_date)
                            @php
                                $isOverdue = $tl->due_date->lt(now()) && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);
                            @endphp
                            <p class="text-sm font-semibold {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-zinc-100' }}">
                                {{ $tl->due_date->format('d F Y') }}
                                @if($isOverdue)
                                    <span class="text-xs font-normal text-red-500">(Overdue)</span>
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-zinc-400 italic">Belum diisi</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    @if($tl->foto_bukti_path)
                        <div>
                            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-2">Foto Bukti</p>
                            <div class="rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                                <img src="{{ Storage::disk('public')->url($tl->foto_bukti_path) }}"
                                     alt="Foto bukti tindak lanjut"
                                     class="w-full max-h-48 object-cover" />
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 p-3 rounded-lg bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 text-orange-700 dark:text-orange-300 text-xs">
                            <flux:icon.exclamation-triangle variant="outline" class="w-4 h-4 shrink-0" />
                            Foto bukti belum diupload
                        </div>
                    @endif

                    @if($tl->catatan_qa)
                        <div>
                            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Catatan QA</p>
                            <div class="p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 text-sm text-amber-800 dark:text-amber-200">
                                {{ $tl->catatan_qa }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Panel: Form TindakLanjutPIC (hanya untuk PIC, selama belum closed_acc) --}}
    @if($showTindakLanjutForm)
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <flux:icon.pencil-square variant="outline" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Form Tindak Lanjut PIC</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Isi detail tindakan perbaikan dan update status</p>
                </div>
            </div>

            <div class="p-6">
                <livewire:tindak-lanjut-p-i-c :temuanId="$temuan->id" :key="'tl-' . $temuan->id" />
            </div>
        </div>
    @endif

    {{-- Info: Read-only untuk Pelapor atau QA yang melihat --}}
    @if($isPelapor && !$isPic)
        <div class="flex items-center gap-2 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300 text-sm">
            <flux:icon.information-circle variant="outline" class="w-5 h-5 shrink-0" />
            <span>Anda adalah pelapor temuan ini. Tindak lanjut dilakukan oleh PIC yang ditunjuk.</span>
        </div>
    @endif

    @if($isQa && $temuan->status === 'closed_pending_qa')
        <livewire:verifikasi-q-a :temuan="$temuan" :key="'vqa-' . $temuan->id" />
    @elseif($isQa)
        <div class="flex items-center gap-2 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 text-amber-700 dark:text-amber-300 text-sm mt-6">
            <flux:icon.shield-check variant="outline" class="w-5 h-5 shrink-0" />
            <span>Verifikasi QA hanya bisa dilakukan saat status temuan adalah Pending QA (closed_pending_qa).</span>
        </div>
    @endif
</div>
