<div class="max-w-4xl mx-auto space-y-6" id="detail-temuan-container">
    {{-- Header + Breadcrumb --}}
    <div class="flex items-center gap-2 font-label-md text-label-md text-on-surface-variant">
        <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-on-surface font-semibold">Temuan #{{ $temuan->id }}</span>
    </div>

    {{-- Card: Info Temuan --}}
    <div class="bg-surface rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between bg-surface-container-low">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary-container text-[20px]">description</span>
                </div>
                <div>
                    <h1 class="font-title-md text-title-md text-on-surface">Detail Temuan PRP #{{ $temuan->id }}</h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Dilaporkan {{ $temuan->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Status Badge --}}
            @php
                $statusBadgeClass = match($temuan->status) {
                    'open'              => 'bg-[#fef7e0] text-[#b06000] border-[#fde293]',
                    'in_progress'       => 'bg-secondary-container text-on-secondary-container border-outline-variant',
                    'closed_pending_qa' => 'bg-[#fce8e6] text-[#c5221f] border-[#f2b8b5]',
                    'closed_acc'        => 'bg-[#e6f4ea] text-[#137333] border-[#ceead6]',
                    default             => 'bg-surface-variant text-on-surface-variant border-outline-variant',
                };
                $statusText = match($temuan->status) {
                    'open'              => 'Open',
                    'in_progress'       => 'In Progress',
                    'closed_pending_qa' => 'Pending QA',
                    'closed_acc'        => 'Closed (ACC)',
                    default             => $temuan->status,
                };
            @endphp
            <span class="px-3 py-1 font-label-md text-label-md rounded-full border {{ $statusBadgeClass }}">
                {{ $statusText }}
            </span>
        </div>

        {{-- Card Body: Info Grid --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Kolom Kiri --}}
            <div class="space-y-4">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Tanggal Temuan</p>
                    <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $temuan->tanggal_temuan->format('d F Y') }}</p>
                </div>

                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Departemen</p>
                    <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $temuan->departemen->nama_departemen ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Sub Area</p>
                    <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $temuan->sub_area }}</p>
                </div>

                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Klausul PRP</p>
                    <p class="font-body-md text-body-md text-on-surface font-semibold">
                        {{ $temuan->klausul ? $temuan->klausul->kode_klausul . ' — ' . $temuan->klausul->nama_klausul : '-' }}
                    </p>
                </div>

                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Pelapor</p>
                    <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $temuan->pelapor->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">PIC yang Ditunjuk</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-on-primary text-xs font-bold">
                            {{ substr($temuan->pic->name ?? '?', 0, 1) }}
                        </div>
                        <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $temuan->pic->name ?? '-' }}</p>
                        @if($isPic)
                            <span class="px-1.5 py-0.5 font-label-md text-[10px] bg-primary-container text-on-primary-container rounded border border-primary/20">Anda</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Deskripsi + Foto --}}
            <div class="space-y-4">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Deskripsi Temuan</p>
                    <p class="font-body-md text-body-md text-on-surface leading-relaxed whitespace-pre-wrap bg-surface-container-low p-3 rounded-lg border border-outline-variant/50">{{ $temuan->deskripsi }}</p>
                </div>

                @if($temuan->saran)
                    <div>
                        <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Saran & Masukan (Langsung ke QA)</p>
                        <p class="font-body-md text-body-md text-on-surface leading-relaxed whitespace-pre-wrap bg-surface-container-low p-3 rounded-lg border border-outline-variant/50">{{ $temuan->saran }}</p>
                    </div>
                @endif

                @if($temuan->foto_temuan_path)
                    <div>
                        <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-2">Foto Temuan</p>
                        <div class="rounded-lg overflow-hidden border border-outline-variant bg-surface-container-low">
                            <img src="{{ asset('storage/' . $temuan->foto_temuan_path) }}"
                                 alt="Foto temuan PRP"
                                 class="w-full max-h-72 object-contain" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Card: Tindak Lanjut (info ringkas, selalu ditampilkan) --}}
    @php $tl = $temuan->tindakLanjut; @endphp
    @if($tl)
        <div class="bg-surface rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex items-center gap-3 bg-surface-container-low">
                <div class="w-9 h-9 rounded-full bg-secondary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-secondary-container text-[20px]">task_alt</span>
                </div>
                <h2 class="font-title-md text-title-md text-on-surface">Tindak Lanjut</h2>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Tindakan Perbaikan</p>
                        @if($tl->action)
                            <p class="font-body-md text-body-md text-on-surface whitespace-pre-wrap bg-surface-container-low p-3 rounded-lg border border-outline-variant/50">{{ $tl->action }}</p>
                        @else
                            <p class="font-body-sm text-body-sm text-on-surface-variant italic">Belum diisi</p>
                        @endif
                    </div>

                    <div>
                        <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Due Date</p>
                        @if($tl->due_date)
                            @php
                                $isOverdue = $tl->due_date->lt(now()) && !in_array($temuan->status, ['closed_pending_qa', 'closed_acc']);
                            @endphp
                            <p class="font-body-md text-body-md font-semibold {{ $isOverdue ? 'text-error' : 'text-on-surface' }}">
                                {{ $tl->due_date->format('d F Y') }}
                                @if($isOverdue)
                                    <span class="font-label-md text-[10px] text-error border border-error/50 px-1 py-0.5 rounded ml-1">Overdue</span>
                                @endif
                            </p>
                        @else
                            <p class="font-body-sm text-body-sm text-on-surface-variant italic">Belum diisi</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    @if($tl->foto_bukti_path)
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-2">Foto Bukti</p>
                            <div class="rounded-lg overflow-hidden border border-outline-variant bg-surface-container-low">
                                <img src="{{ asset('storage/' . $tl->foto_bukti_path) }}"
                                     alt="Foto bukti tindak lanjut"
                                     class="w-full max-h-48 object-contain" />
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 p-3 rounded-lg bg-[#fef7e0] border border-[#fde293] text-[#b06000] text-xs">
                            <span class="material-symbols-outlined text-[16px] shrink-0">warning</span>
                            Foto bukti belum diupload
                        </div>
                    @endif

                    @if($tl->catatan_qa)
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wide mb-1">Catatan QA</p>
                            <div class="p-3 rounded-lg bg-surface-container-highest border border-outline-variant font-body-sm text-on-surface">
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
        <div class="bg-surface rounded-xl shadow-[0px_2px_4px_rgba(0,139,157,0.05),0px_4px_12px_rgba(0,0,0,0.03)] border border-outline-variant overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex items-center gap-3 bg-surface-container-low">
                <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary-container text-[20px]">edit_document</span>
                </div>
                <div>
                    <h2 class="font-title-md text-title-md text-on-surface">Form Tindak Lanjut PIC</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Isi detail tindakan perbaikan dan update status</p>
                </div>
            </div>

            <div class="p-6">
                <livewire:tindak-lanjut-p-i-c :temuanId="$temuan->id" :key="'tl-' . $temuan->id" />
            </div>
        </div>
    @endif

    {{-- Info: Read-only untuk Pelapor atau QA yang melihat --}}
    @if($isPelapor && !$isPic)
        <div class="flex items-center gap-2 p-4 rounded-lg bg-secondary-container border border-outline-variant text-on-secondary-container font-body-sm">
            <span class="material-symbols-outlined text-[20px] shrink-0">info</span>
            <span>Anda adalah pelapor temuan ini. Tindak lanjut dilakukan oleh PIC yang ditunjuk.</span>
        </div>
    @endif

    @if($isQa && $temuan->status === 'closed_pending_qa')
        <livewire:verifikasi-q-a :temuan="$temuan" :key="'vqa-' . $temuan->id" />
    @elseif($isQa)
        <div class="flex items-center gap-2 p-4 rounded-lg bg-[#fef7e0] border border-[#fde293] text-[#b06000] font-body-sm mt-6">
            <span class="material-symbols-outlined text-[20px] shrink-0">shield</span>
            <span>Verifikasi QA hanya bisa dilakukan saat status temuan adalah Pending QA (closed_pending_qa).</span>
        </div>
    @endif
</div>
