<div style="display:flex;flex-direction:column;gap:24px;" class="fu">
    
    {{-- Header & Title --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Rekap Kepatuhan Target Laporan — BOS'Q</h2>
            <p class="bph-sub">Pemantauan target 2 laporan/minggu per Anggota Divisi Manajemen (Periode: <strong>{{ $weekLabel }}</strong>)</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('bosq.qa.export.rekap.csv', ['date' => $selected_date]) }}" class="bbtn bbtn-secondary bbtn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;color:#2e7d32;">csv</span>
                Export Excel / CSV
            </a>
            <a href="{{ route('bosq.qa.export.rekap.pdf', ['date' => $selected_date]) }}" target="_blank" class="bbtn bbtn-primary bbtn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;">picture_as_pdf</span>
                Export PDF Rekap
            </a>
        </div>
    </div>

    {{-- Filter Minggu Card --}}
    <div class="bcard fu1" style="padding:16px 20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="color:var(--bp-dark);font-size:20px;">calendar_month</span>
                <span style="font-size:13.5px;font-weight:700;color:var(--btxt);">Pilih Minggu Target:</span>
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <button wire:click="prevWeek" class="bbtn bbtn-secondary bbtn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;">chevron_left</span>
                    Minggu Sebelumnya
                </button>

                <input type="date" wire:model.live="selected_date" class="binput" style="width:auto;padding:6px 12px;font-size:13px;font-weight:600;" />

                <button wire:click="currentWeek" class="bbtn bbtn-secondary bbtn-sm" style="font-size:12px;">
                    Minggu Ini
                </button>

                <button wire:click="nextWeek" class="bbtn bbtn-secondary bbtn-sm">
                    Minggu Selanjutnya
                    <span class="material-symbols-outlined" style="font-size:16px;">chevron_right</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Stat Cards Summary --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;" class="fu2">
        <div class="bstat">
            <div class="bstat-icon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:22px;">flag</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#1565c0;">{{ $totalTargetSemua }}</div>
                <div class="bstat-lbl">Total Target Laporan</div>
            </div>
        </div>

        <div class="bstat">
            <div class="bstat-icon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:22px;">task_alt</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#2e7d32;">{{ $totalRealisasiSemua }}</div>
                <div class="bstat-lbl">Total Realisasi Disubmit</div>
            </div>
        </div>

        <div class="bstat">
            <div class="bstat-icon" style="background:#fff3e0;">
                <span class="material-symbols-outlined fil" style="color:#e65100;font-size:22px;">percent</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#e65100;">
                    {{ $totalTargetSemua > 0 ? min(100, round(($totalRealisasiSemua / $totalTargetSemua) * 100, 1)) . '%' : 'N/A' }}
                </div>
                <div class="bstat-lbl">Pencapaian Keseluruhan</div>
            </div>
        </div>
    </div>

    {{-- Tabel Rekap Kepatuhan (Level 1 Departemen & Level 2 Drill-down Individu) --}}
    <div class="bcard fu2">
        <div class="bcard-header">
            <div class="bcard-hicon" style="background:var(--bp-light);">
                <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">equalizer</span>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Laporan Kepatuhan Per Departemen & Individu</h3>
                <p style="font-size:12px;color:var(--btxt2);margin:0;">Klik tombol drill-down di sebelah kanan departemen untuk melihat detail target per anggota</p>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;text-align:left;">
                <thead>
                    <tr style="background:var(--bsur);border-bottom:1px solid var(--bbor);color:var(--btxt2);font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                        <th style="padding:12px 16px;">Departemen</th>
                        <th style="padding:12px 16px;text-align:center;">Jumlah Anggota</th>
                        <th style="padding:12px 16px;text-align:center;">Target Mingguan</th>
                        <th style="padding:12px 16px;text-align:center;">Realisasi</th>
                        <th style="padding:12px 16px;text-align:center;">Persentase</th>
                        <th style="padding:12px 16px;text-align:center;">Status Kepatuhan</th>
                        <th style="padding:12px 16px;text-align:center;">Drill-Down</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapData as $row)
                        @php
                            $isExpanded = $expanded_departemen_id === $row['departemen_id'];
                        @endphp
                        <tr style="border-bottom:1px solid var(--bbor);{{ $isExpanded ? 'background:var(--bp-light);' : '' }}">
                            <td style="padding:12px 16px;font-weight:700;color:var(--btxt);">
                                {{ $row['nama'] }}
                            </td>
                            <td style="padding:12px 16px;text-align:center;font-weight:600;">
                                {{ $row['anggota_count'] }} Karyawan
                            </td>
                            <td style="padding:12px 16px;text-align:center;font-weight:600;color:var(--bp);">
                                {{ $row['target'] }}
                            </td>
                            <td style="padding:12px 16px;text-align:center;font-weight:700;color:{{ $row['realisasi'] >= $row['target'] && $row['target'] > 0 ? '#2e7d32' : 'var(--btxt)' }}">
                                {{ $row['realisasi'] }}
                            </td>
                            <td style="padding:12px 16px;text-align:center;font-weight:700;">
                                {{ $row['persentase'] }}
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                @if($row['status'] === 'no_members')
                                    <span style="font-size:11px;font-weight:700;background:var(--bsur);color:var(--btxt2);padding:4px 10px;border-radius:20px;border:1px solid var(--bbor);display:inline-flex;align-items:center;gap:4px;">
                                        <span class="material-symbols-outlined" style="font-size:14px;">info</span>
                                        Belum ada anggota terdaftar
                                    </span>
                                @elseif($row['status'] === 'tercapai')
                                    <span style="font-size:11px;font-weight:700;background:#e8f5e9;color:#2e7d32;padding:4px 10px;border-radius:20px;border:1px solid #a5d6a7;display:inline-flex;align-items:center;gap:4px;">
                                        <span class="material-symbols-outlined" style="font-size:14px;">check_circle</span>
                                        Target Tercapai
                                    </span>
                                @else
                                    <span style="font-size:11px;font-weight:700;background:#fff3e0;color:#e65100;padding:4px 10px;border-radius:20px;border:1px solid #ffe0b2;display:inline-flex;align-items:center;gap:4px;">
                                        <span class="material-symbols-outlined" style="font-size:14px;">warning</span>
                                        Belum Tercapai
                                    </span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                @if($row['anggota_count'] > 0)
                                    <button wire:click="toggleExpand({{ $row['departemen_id'] }})" class="bbtn bbtn-secondary bbtn-sm">
                                        <span class="material-symbols-outlined" style="font-size:16px;">{{ $isExpanded ? 'expand_less' : 'unfold_more' }}</span>
                                        <span>{{ $isExpanded ? 'Tutup Detail' : 'Detail Anggota (' . count($row['individu_list']) . ')' }}</span>
                                    </button>
                                @else
                                    <span style="font-size:11.5px;color:var(--btxt2);">-</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Level 2: Drill-down Sub-Table (Individu) --}}
                        @if($isExpanded && $row['anggota_count'] > 0)
                            <tr style="background:#f8fafc;border-bottom:2px solid var(--bbor);">
                                <td colspan="7" style="padding:16px 24px;">
                                    <div style="background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;padding:14px;box-shadow:0 2px 8px rgba(0,0,0,0.03);">
                                        <div style="font-size:13px;font-weight:700;color:var(--btxt);margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                                            <span class="material-symbols-outlined" style="font-size:18px;color:var(--bp);">person_search</span>
                                            <span>Detail Target Individu — Departemen {{ $row['nama'] }}</span>
                                        </div>

                                        <table style="width:100%;border-collapse:collapse;font-size:12.5px;text-align:left;">
                                            <thead>
                                                <tr style="background:var(--bsur);border-bottom:1px solid var(--bbor);color:var(--btxt2);font-size:10.5px;text-transform:uppercase;">
                                                    <th style="padding:8px 12px;">NIK & Nama Anggota</th>
                                                    <th style="padding:8px 12px;text-align:center;">Target Individu</th>
                                                    <th style="padding:8px 12px;text-align:center;">Realisasi Observasi</th>
                                                    <th style="padding:8px 12px;text-align:center;">Persentase</th>
                                                    <th style="padding:8px 12px;text-align:center;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($row['individu_list'] as $ind)
                                                    <tr style="border-bottom:1px solid var(--bbor);">
                                                        <td style="padding:8px 12px;">
                                                            <div style="font-weight:600;color:var(--btxt);">{{ $ind['nama'] }}</div>
                                                            <div style="font-size:11px;color:var(--btxt2);">NIK: {{ $ind['nik'] }}</div>
                                                        </td>
                                                        <td style="padding:8px 12px;text-align:center;font-weight:600;">
                                                            {{ $ind['target'] }} Laporan
                                                        </td>
                                                        <td style="padding:8px 12px;text-align:center;font-weight:700;color:{{ $ind['realisasi'] >= $ind['target'] ? '#2e7d32' : '#c62828' }}">
                                                            {{ $ind['realisasi'] }} Laporan
                                                        </td>
                                                        <td style="padding:8px 12px;text-align:center;font-weight:700;">
                                                            {{ $ind['persentase'] }}%
                                                        </td>
                                                        <td style="padding:8px 12px;text-align:center;">
                                                            @if($ind['status'] === 'tercapai')
                                                                <span style="font-size:10.5px;font-weight:700;background:#e8f5e9;color:#2e7d32;padding:2px 8px;border-radius:12px;border:1px solid #a5d6a7;display:inline-flex;align-items:center;gap:3px;">
                                                                    <span class="material-symbols-outlined" style="font-size:12px;">check</span>
                                                                    Tercapai
                                                                </span>
                                                            @else
                                                                <span style="font-size:10.5px;font-weight:700;background:#fff3e0;color:#e65100;padding:2px 8px;border-radius:12px;border:1px solid #ffe0b2;display:inline-flex;align-items:center;gap:3px;">
                                                                    <span class="material-symbols-outlined" style="font-size:12px;">hourglass_empty</span>
                                                                    Belum
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
