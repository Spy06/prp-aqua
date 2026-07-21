<div class="fu">
    <div class="bph">
        <div>
            <h2 class="bph-title">Daftar Semua Temuan</h2>
            <p class="bph-sub">Monitor seluruh temuan yang tercatat dalam sistem</p>
        </div>
    </div>

    <div class="bcard" style="padding:20px;">
        {{-- Filter Row --}}
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
            <select wire:model.live="filterDepartemen" class="binput" style="width:auto;min-width:160px;">
                <option value="">Semua Departemen</option>
                @foreach($departemens as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStatus" class="binput" style="width:auto;min-width:140px;">
                <option value="">Semua Status</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="closed_pending_qa">Pending QA</option>
                <option value="closed_acc">Closed (ACC)</option>
            </select>
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto;">
            <table class="btbl" style="min-width:600px;">
                <thead>
                    <tr>
                        <th>Tgl Temuan</th>
                        <th>Departemen & Area</th>
                        <th>PIC</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temuans as $t)
                    <tr>
                        <td style="white-space:nowrap;">{{ $t->tanggal_temuan->format('d M Y') }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $t->departemen->nama_departemen ?? '-' }}</div>
                            <div style="font-size:12px;color:var(--btxt2);">
                                {{ $t->sub_area }}
                                @if($t->sub_area === 'Others' && $t->detail_sub_area)
                                    — {{ $t->detail_sub_area }}
                                @endif
                            </div>
                        </td>
                        <td style="white-space:nowrap;">{{ $t->pic->name ?? '-' }}</td>
                        <td>
                            @php
                                $badgeClass = match($t->status) {
                                    'open'              => 'bbadge-open',
                                    'in_progress'       => 'bbadge-progress',
                                    'closed_pending_qa' => 'bbadge-pending',
                                    'closed_acc'        => 'bbadge-closed',
                                    default             => '',
                                };
                                $badgeText = match($t->status) {
                                    'open'              => 'Open',
                                    'in_progress'       => 'In Progress',
                                    'closed_pending_qa' => 'Pending QA',
                                    'closed_acc'        => 'Closed (ACC)',
                                    default             => $t->status,
                                };
                            @endphp
                            <span class="bbadge {{ $badgeClass }}">{{ $badgeText }}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <a href="{{ route('temuan.detail', $t->id) }}"
                                   class="bbtn bbtn-primary bbtn-sm">
                                    <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                                    Detail
                                </a>
                                <a href="{{ route('export.pdf.temuan', $t->id) }}"
                                   target="_blank"
                                   class="bbtn bbtn-danger bbtn-sm">
                                    <span class="material-symbols-outlined" style="font-size:14px;">picture_as_pdf</span>
                                    PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.4;">inbox</span>
                            Tidak ada data temuan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;">
            {{ $temuans->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
