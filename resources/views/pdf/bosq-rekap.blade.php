<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kepatuhan BOS'Q — {{ $weekLabel }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9.5px; color: #1f2937; background: #fff; padding: 20px; }
        .header { border-bottom: 3px solid #1976d2; padding-bottom: 12px; margin-bottom: 16px; }
        .company-name { font-size: 15px; font-weight: bold; color: #1565c0; }
        .doc-title { font-size: 13px; font-weight: bold; color: #1e3a8a; text-align: right; }
        .doc-meta { font-size: 9px; color: #6b7280; text-align: right; margin-top: 3px; }

        .stat-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .stat-table td { width: 33.3%; padding: 8px; border: 1px solid #e5e7eb; text-align: center; border-radius: 4px; }
        .stat-val { font-size: 16px; font-weight: bold; }
        .stat-lbl { font-size: 8px; color: #6b7280; text-transform: uppercase; margin-top: 2px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 16px; }
        table.data-table th { background: #f3f4f6; padding: 6px 8px; text-align: left; font-size: 8.5px; text-transform: uppercase; color: #4b5563; border-bottom: 1px solid #d1d5db; }
        table.data-table td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 8.5px; vertical-align: middle; }

        .badge { font-weight: bold; padding: 2px 6px; border-radius: 10px; font-size: 7.5px; display: inline-block; }
        .badge-tercapai { background: #e8f5e9; color: #2e7d32; }
        .badge-belum { background: #fff3e0; color: #e65100; }
        .badge-none { background: #f3f4f6; color: #6b7280; }

        .ind-table { width: 100%; border-collapse: collapse; margin-top: 4px; background: #f8fafc; }
        .ind-table th { background: #e2e8f0; padding: 4px 6px; font-size: 8px; color: #475569; }
        .ind-table td { padding: 4px 6px; font-size: 8px; border-bottom: 1px solid #cbd5e1; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width:100%;">
            <tr>
                <td>
                    <div class="company-name">PRP PLANT AQUA</div>
                    <div style="font-size:10px;font-weight:bold;color:#4b5563;margin-top:2px;">BOS'Q — Behavior Observation System Quality</div>
                </td>
                <td>
                    <div class="doc-title">REKAP KEPATUHAN TARGET</div>
                    <div class="doc-meta">Periode: {{ $weekLabel }} | Diunduh: {{ now()->translatedFormat('d F Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Summary Cards --}}
    <table class="stat-table">
        <tr>
            <td>
                <div class="stat-val" style="color:#1565c0;">{{ $totalTarget }}</div>
                <div class="stat-lbl">Total Target Laporan</div>
            </td>
            <td>
                <div class="stat-val" style="color:#2e7d32;">{{ $totalRealisasi }}</div>
                <div class="stat-lbl">Total Realisasi Disubmit</div>
            </td>
            <td>
                <div class="stat-val" style="color:#e65100;">
                    {{ $totalTarget > 0 ? min(100, round(($totalRealisasi / $totalTarget) * 100, 1)) . '%' : 'N/A' }}
                </div>
                <div class="stat-lbl">Pencapaian Keseluruhan</div>
            </td>
        </tr>
    </table>

    <div style="font-size:10px;font-weight:bold;color:#1565c0;margin-bottom:6px;text-transform:uppercase;">Ringkasan Kepatuhan Departemen & Anggota</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:25%;">Departemen</th>
                <th style="width:12%;text-align:center;">Anggota</th>
                <th style="width:12%;text-align:center;">Target</th>
                <th style="width:12%;text-align:center;">Realisasi</th>
                <th style="width:15%;text-align:center;">Status Dept</th>
                <th style="width:24%;">Detail Anggota (Rincian Target)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $row)
                <tr>
                    <td style="font-weight:bold;">{{ $row['departemen'] }}</td>
                    <td style="text-align:center;">{{ $row['anggota_count'] }} Orang</td>
                    <td style="text-align:center;font-weight:bold;color:#1565c0;">{{ $row['target'] }}</td>
                    <td style="text-align:center;font-weight:bold;color:{{ $row['realisasi'] >= $row['target'] && $row['target'] > 0 ? '#2e7d32' : '#1f2937' }}">{{ $row['realisasi'] }}</td>
                    <td style="text-align:center;">
                        @if($row['status'] === 'no_members')
                            <span class="badge badge-none">Tanpa Anggota</span>
                        @elseif($row['status'] === 'tercapai')
                            <span class="badge badge-tercapai">✅ Tercapai</span>
                        @else
                            <span class="badge badge-belum">⚠️ Belum</span>
                        @endif
                    </td>
                    <td>
                        @if($row['anggota_count'] > 0)
                            <table class="ind-table">
                                <thead>
                                    <tr>
                                        <th>Nama Karyawan</th>
                                        <th style="text-align:center;">Target</th>
                                        <th style="text-align:center;">Realisasi</th>
                                        <th style="text-align:center;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($row['individu_list'] as $ind)
                                        <tr>
                                            <td>{{ $ind['nama'] }}</td>
                                            <td style="text-align:center;">2</td>
                                            <td style="text-align:center;font-weight:bold;">{{ $ind['realisasi'] }}</td>
                                            <td style="text-align:center;">
                                                <span class="badge {{ $ind['status'] === 'Tercapai' ? 'badge-tercapai' : 'badge-belum' }}">{{ $ind['status'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <span style="font-style:italic;color:#9ca3af;">Belum ada anggota terdaftar</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
