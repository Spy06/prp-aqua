<div style="display:flex;flex-direction:column;gap:20px;" class="fu">
    
    {{-- Header & Title --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Daftar Observasi Mutu — BOS'Q</h2>
            <p class="bph-sub">Kelola dan tinjau seluruh data hasil observasi perilaku mutu (Periode: <strong>{{ $filterLabel }}</strong>)</p>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('bosq.qa.export.csv', [
                'tipe' => $filter_type,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'awal' => $tgl_mulai,
                'akhir' => $tgl_selesai,
                'departemen_id' => $filter_departemen_id,
                'status' => $filter_status,
                'tingkat_resiko' => $filter_tingkat_resiko,
                'dampak_temuan' => $filter_dampak_temuan
            ]) }}" class="bbtn" style="background:#10b981;color:#ffffff;border:none;border-radius:20px;padding:7px 16px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;box-shadow:0 2px 6px rgba(16,185,129,0.25);">
                <span class="material-symbols-outlined" style="font-size:16px;color:#fff;">table_chart</span> Excel
            </a>
            <a href="{{ route('bosq.qa.export.pdf.dashboard', [
                'tipe' => $filter_type,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'awal' => $tgl_mulai,
                'akhir' => $tgl_selesai,
                'departemen_id' => $filter_departemen_id,
                'status' => $filter_status,
                'tingkat_resiko' => $filter_tingkat_resiko,
                'dampak_temuan' => $filter_dampak_temuan
            ]) }}" target="_blank" class="bbtn" style="background:#d83b01;color:#ffffff;border:none;border-radius:20px;padding:7px 16px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;box-shadow:0 2px 6px rgba(216,59,1,0.25);">
                <span class="material-symbols-outlined" style="font-size:16px;color:#fff;">picture_as_pdf</span> PDF
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bcard fu1" style="padding:16px 20px;">
        <div style="display:flex;flex-direction:column;gap:12px;">
            <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="color:var(--bp-dark);font-size:20px;">filter_alt</span>
                    <span style="font-size:13px;font-weight:700;color:var(--btxt);">Filter Periode:</span>
                </div>

                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;flex:1;">
                    <select wire:model.live="filter_type" class="binput" style="width:auto;padding:7px 12px;font-size:13px;">
                        <option value="bulan">Per Bulan</option>
                        <option value="tahun">Per Tahun</option>
                        <option value="custom">Rentang Tanggal Custom</option>
                    </select>

                    @if($filter_type === 'bulan')
                        <select wire:model.live="bulan" class="binput" style="width:auto;padding:7px 12px;font-size:13px;">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}</option>
                            @endfor
                        </select>

                        <select wire:model.live="tahun" class="binput" style="width:auto;padding:7px 12px;font-size:13px;">
                            @for($y = now()->year; $y >= 2024; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    @elseif($filter_type === 'tahun')
                        <select wire:model.live="tahun" class="binput" style="width:auto;padding:7px 12px;font-size:13px;">
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
                </div>
            </div>

            {{-- Filter Kategori --}}
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding-top:10px;border-top:1px solid var(--bbor);">
                <select wire:model.live="filter_departemen_id" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filter_sub_area_id" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Sub Area</option>
                    @foreach($subAreas as $sa)
                        <option value="{{ $sa->id }}">{{ $sa->nama_sub_area }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filter_status" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Status</option>
                    <option value="open">Status Open</option>
                    <option value="closed">Status Closed</option>
                </select>

                <select wire:model.live="filter_dampak_temuan" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Dampak</option>
                    <option value="negatif">Negatif (Butuh Perbaikan)</option>
                    <option value="positif">Positif (Perilaku Baik)</option>
                </select>

                <div style="flex:1;min-width:200px;position:relative;">
                    <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari pelapor, NIK, atau detail..." class="binput" style="padding-left:36px;padding-top:6px;padding-bottom:6px;font-size:12.5px;" />
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Semua Temuan --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl" style="min-width:760px;">
                <thead>
                    <tr style="background:var(--bsur);">
                        <th style="padding:12px 16px;text-align:left;">No / Tgl</th>
                        <th style="padding:12px 16px;text-align:left;">Departemen & Sub Area</th>
                        <th style="padding:12px 16px;text-align:left;">Pelapor</th>
                        <th style="padding:12px 16px;text-align:center;">Dampak</th>
                        <th style="padding:12px 16px;text-align:center;">Status</th>
                        <th style="padding:12px 16px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temuans as $t)
                        @php
                            $isClosed = in_array($t->status, ['closed', 'closed_acc']);
                            $statusBadgeClass = $isClosed ? 'sbadge-closed' : 'sbadge-open';
                            $statusText = $isClosed ? 'CLOSED' : 'OPEN';
                        @endphp
                        <tr style="border-bottom:1px solid var(--bbor);transition:background 0.15s;" onmouseover="this.style.background='var(--bsur)'" onmouseout="this.style.background='none'">
                            <td style="padding:12px 16px;">
                                <div style="font-weight:700;color:var(--bp);">#{{ $t->id }}</div>
                                <div style="font-size:11.5px;color:var(--btxt2);">{{ $t->tanggal_temuan->format('d/m/Y') }}</div>
                            </td>
                            <td style="padding:12px 16px;">
                                <div style="font-weight:600;color:var(--btxt);">{{ $t->departemen->nama_departemen ?? '-' }}</div>
                                <div style="font-size:12px;color:var(--btxt2);">
                                    {{ $t->subArea->nama_sub_area ?? '-' }}
                                    @if($t->detail_sub_area) <span>({{ $t->detail_sub_area }})</span> @endif
                                </div>
                            </td>
                            <td style="padding:12px 16px;">
                                <div style="font-weight:600;font-size:13px;">{{ $t->pelapor->name ?? '-' }}</div>
                                <div style="font-size:11.5px;color:var(--btxt2);">{{ $t->pelapor->departemen->nama_departemen ?? '-' }}</div>
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                @if($t->dampak_temuan === 'positif')
                                    <span style="font-size:11px;font-weight:700;background:#e3f2fd;color:#1565c0;padding:4px 10px;border-radius:6px;border:1px solid #bbdefb;text-transform:uppercase;display:inline-block;">POSITIF</span>
                                @else
                                    <span style="font-size:11px;font-weight:700;background:#ffebee;color:#c62828;padding:4px 10px;border-radius:6px;border:1px solid #ffcdd2;text-transform:uppercase;display:inline-block;">NEGATIF</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <span class="sbadge {{ $statusBadgeClass }}">{{ $statusText }}</span>
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <a href="{{ route('bosq.temuan.detail', $t->id) }}" class="bbtn bbtn-secondary bbtn-sm" style="text-decoration:none;">
                                    <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:36px;text-align:center;color:var(--btxt2);">
                                <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">search_off</span>
                                Belum ada data observasi yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($temuans->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--bbor);">
                {{ $temuans->links() }}
            </div>
        @endif
    </div>

</div>
