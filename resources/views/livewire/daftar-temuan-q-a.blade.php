<div class="fu">
    <div class="bph">
        <div>
            <h2 class="bph-title">Daftar Semua Temuan</h2>
            <p class="bph-sub">Monitor seluruh temuan yang tercatat dalam sistem (Periode: <strong>{{ $filterLabel }}</strong>)</p>
        </div>
    </div>

    <div class="bcard" style="padding:20px;overflow:visible!important;position:relative;z-index:100;">
        {{-- Unified Filter Row (Matching BOS'Q & SIVERA Clean Style) --}}
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:10px;margin-bottom:16px;position:relative;z-index:105;">
            
            {{-- Filter Periode Selects --}}
            <select wire:model.live="filter_type" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                <option value="bulan">Per Bulan</option>
                <option value="tahun">Per Tahun</option>
                <option value="custom">Rentang Tanggal Custom</option>
            </select>

            @if($filter_type === 'bulan')
                <select wire:model.live="bulan" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    @php
                        $bulanNames = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($bulanNames as $mKey => $mName)
                        <option value="{{ $mKey }}">{{ $mName }}</option>
                    @endforeach
                </select>

                <select wire:model.live="tahun" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    @for($y = now()->year; $y >= 2024; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            @elseif($filter_type === 'tahun')
                <select wire:model.live="tahun" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    @for($y = now()->year; $y >= 2024; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            @elseif($filter_type === 'custom')
                <div style="display:flex;align-items:center;gap:6px;">
                    <input type="date" wire:model.live="tgl_mulai" class="binput" style="width:auto;padding:6px 10px;font-size:12.5px;">
                    <span style="font-size:12px;color:var(--btxt2);">s/d</span>
                    <input type="date" wire:model.live="tgl_selesai" class="binput" style="width:auto;padding:6px 10px;font-size:12.5px;">
                </div>
            @endif

            {{-- Departemen Filter --}}
            <select wire:model.live="filterDepartemen" class="binput" style="width:auto;min-width:160px;padding:6px 12px;font-size:12.5px;">
                <option value="">Semua Departemen</option>
                @foreach($departemens as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                @endforeach
            </select>

            {{-- Simpel Native-Style Multi-Select Sub Area Dropdown --}}
            <div style="position:relative;" x-data="{ open: false }" @click.outside="open = false">
                <div @click="open = !open" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;display:inline-flex;align-items:center;gap:14px;cursor:pointer;background:var(--bcard);user-select:none;justify-content:space-between;min-width:150px;">
                    <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:160px;font-weight:{{ count(array_filter($filterSubAreaNames)) > 0 ? '700' : '400' }};color:{{ count(array_filter($filterSubAreaNames)) > 0 ? 'var(--bp)' : 'var(--btxt)' }};">
                        @php $count = count(array_filter($filterSubAreaNames)); @endphp
                        @if($count === 0)
                            Semua Sub Area
                        @elseif($count === 1)
                            {{ reset($filterSubAreaNames) }}
                        @else
                            {{ $count }} Sub Area Dipilih
                        @endif
                    </span>
                    <span class="material-symbols-outlined" style="font-size:16px;color:var(--btxt2);transition:transform 0.2s;" :style="open ? 'transform:rotate(180deg)' : ''">expand_more</span>
                </div>

                {{-- Simple Native-Style Dropdown Menu Box --}}
                <div x-show="open" x-cloak x-transition.origin.top.left style="position:absolute;top:calc(100% + 4px);left:0;z-index:9999;background:var(--bcard);border:1px solid var(--bbor);border-radius:8px;box-shadow:0 6px 20px rgba(0,0,0,0.15);min-width:210px;max-height:260px;overflow-y:auto;padding:4px 0;">
                    <label style="display:flex;align-items:center;gap:10px;padding:7px 14px;cursor:pointer;font-size:12.5px;color:var(--btxt);user-select:none;font-weight:700;border-bottom:1px solid var(--bbor);" onmouseover="this.style.background='var(--bp-light)'" onmouseout="this.style.background='none'">
                        <input type="checkbox" wire:click="selectAllSubAreas" {{ count(array_filter($filterSubAreaNames)) === $subAreas->count() && $subAreas->count() > 0 ? 'checked' : '' }} style="width:15px;height:15px;accent-color:var(--bp);cursor:pointer;" />
                        <span>Semua Sub Area</span>
                    </label>
                    @foreach($subAreas as $sa)
                        <label style="display:flex;align-items:center;gap:10px;padding:6px 14px;cursor:pointer;font-size:12.5px;color:var(--btxt);user-select:none;" onmouseover="this.style.background='var(--bp-light)'" onmouseout="this.style.background='none'">
                            <input type="checkbox" wire:model.live="filterSubAreaNames" value="{{ $sa->nama_sub_area }}" style="width:15px;height:15px;accent-color:var(--bp);cursor:pointer;" />
                            <span>{{ $sa->nama_sub_area }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Status Filter --}}
            <select wire:model.live="filterStatus" class="binput" style="width:auto;min-width:140px;padding:6px 12px;font-size:12.5px;">
                <option value="">Semua Status</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="closed_pending_qa">Pending QA</option>
                <option value="closed_acc">Closed (ACC)</option>
            </select>

            {{-- Search Bar --}}
            <div style="flex:1;min-width:200px;position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari pelapor, NIK, atau area..." class="binput" style="padding-left:36px;padding-top:6px;padding-bottom:6px;font-size:12.5px;" />
            </div>
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
                            Tidak ada data temuan yang sesuai dengan filter
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
