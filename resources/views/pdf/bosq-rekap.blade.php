<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Target BQA BOS'Q — {{ $monthName }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif; font-size: 9px; color: #1e293b; background: #fff; padding: 18px; }
        .header { border-bottom: 2px solid #0f172a; padding-bottom: 8px; margin-bottom: 14px; }
        .company-name { font-size: 14px; font-weight: bold; color: #0f172a; }
        .doc-title { font-size: 12px; font-weight: bold; color: #0369a1; text-align: right; }
        .doc-meta { font-size: 8.5px; color: #64748b; text-align: right; margin-top: 2px; }

        .section-title { font-size: 10px; font-weight: bold; color: #0f172a; margin-top: 10px; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }

        table.matrix-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; border: 1px solid #94a3b8; }
        table.matrix-table th, table.matrix-table td { border: 1px solid #cbd5e1; padding: 4px 6px; text-align: center; font-size: 8.5px; }
        table.matrix-table th { background: #1e293b; color: #ffffff; font-weight: bold; }

        .dept-title-row { background: #cbd5e1; color: #0f172a; font-weight: bold; text-align: left !important; text-transform: uppercase; }
        .member-name { text-align: left !important; font-weight: bold; color: #1e293b; }

        .score-green { background: #dcfce7; color: #15803d; font-weight: bold; }
        .score-red { background: #fee2e2; color: #b91c1c; font-weight: bold; }

        .score-ind-100 { background: #dcfce7; color: #15803d; font-weight: bold; }
        .score-ind-partial { background: #fef08a; color: #854d0e; font-weight: bold; }
        .score-ind-0 { background: #f1f5f9; color: #64748b; font-weight: bold; }

        .legend-box { border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px; font-size: 8px; font-weight: bold; background: #f8fafc; margin-top: 12px; }
        .legend-item { display: inline-block; margin-right: 12px; padding: 3px 8px; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width:100%;">
            <tr>
                <td>
                    <div class="company-name">PRP PLANT AQUA</div>
                    <div style="font-size:9.5px;font-weight:bold;color:#475569;margin-top:2px;">BOS'Q — Behavior Observation System Quality</div>
                </td>
                <td>
                    <div class="doc-title">PENCAPAIAN BQA — REKAP KEPATUHAN</div>
                    <div class="doc-meta">Periode: {{ $monthName }} | Diunduh: {{ now()->translatedFormat('d F Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- TABEL 1: REKAP RINGKASAN PER DEPARTEMEN --}}
    <div class="section-title">1. REKAP KEPATUHAN PER DEPARTEMEN</div>
    <table class="matrix-table">
        <thead>
            <tr style="background:#0f172a;color:#ffffff;">
                <th style="text-align:left;width:30%;">{{ $monthShort }}</th>
                <th style="width:17.5%;">WEEK 1</th>
                <th style="width:17.5%;">WEEK 2</th>
                <th style="width:17.5%;">WEEK 3</th>
                <th style="width:17.5%;">WEEK 4</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deptSummary as $ds)
                <tr>
                    <td class="member-name">{{ $ds['nama'] }}</td>
                    @php $p1 = $ds['scores']['w1']['persen']; @endphp
                    <td class="{{ $p1 == 100 ? 'score-green' : 'score-red' }}">{{ $p1 }}%</td>

                    @php $p2 = $ds['scores']['w2']['persen']; @endphp
                    <td class="{{ $p2 == 100 ? 'score-green' : 'score-red' }}">{{ $p2 }}%</td>

                    @php $p3 = $ds['scores']['w3']['persen']; @endphp
                    <td class="{{ $p3 == 100 ? 'score-green' : 'score-red' }}">{{ $p3 }}%</td>

                    @php $p4 = $ds['scores']['w4']['persen']; @endphp
                    <td class="{{ $p4 == 100 ? 'score-green' : 'score-red' }}">{{ $p4 }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TABEL 2: DETAIL PENCAPAIAN BQA PER ANGGOTA DEPARTEMEN --}}
    <div class="section-title" style="page-break-before: auto;">2. DETAIL PENCAPAIAN BQA PER ANGGOTA DEPARTEMEN</div>
    <table class="matrix-table">
        <thead>
            <tr style="background:#0f172a;color:#ffffff;">
                <th style="text-align:left;width:30%;">PENCAPAIAN BQA (Sum of % in WEEK)</th>
                <th colspan="4" style="background:#1e293b;color:#38bdf8;">{{ $monthName }}</th>
            </tr>
            <tr style="background:#334155;color:#ffffff;">
                <th style="text-align:left;">Row Labels</th>
                <th>{{ $weeks['w1']['label'] }}</th>
                <th>{{ $weeks['w2']['label'] }}</th>
                <th>{{ $weeks['w3']['label'] }}</th>
                <th>{{ $weeks['w4']['label'] }}</th>
            </tr>
            <tr style="background:#475569;color:#ffffff;">
                <th style="text-align:left;">Column Labels</th>
                <th>WEEK 1</th>
                <th>WEEK 2</th>
                <th>WEEK 3</th>
                <th>WEEK 4</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matrixData as $deptGroup)
                <tr>
                    <td colspan="5" class="dept-title-row">{{ $deptGroup['nama_departemen'] }}</td>
                </tr>
                @forelse($deptGroup['members'] as $m)
                    <tr>
                        <td class="member-name">{{ $m['nama'] }}</td>
                        @php $w1 = $m['scores']['w1']; @endphp
                        <td class="{{ $w1['persen'] == 100 ? 'score-ind-100' : ($w1['persen'] >= 50 ? 'score-ind-partial' : 'score-ind-0') }}">{{ $w1['persen'] }}</td>

                        @php $w2 = $m['scores']['w2']; @endphp
                        <td class="{{ $w2['persen'] == 100 ? 'score-ind-100' : ($w2['persen'] >= 50 ? 'score-ind-partial' : 'score-ind-0') }}">{{ $w2['persen'] }}</td>

                        @php $w3 = $m['scores']['w3']; @endphp
                        <td class="{{ $w3['persen'] == 100 ? 'score-ind-100' : ($w3['persen'] >= 50 ? 'score-ind-partial' : 'score-ind-0') }}">{{ $w3['persen'] }}</td>

                        @php $w4 = $m['scores']['w4']; @endphp
                        <td class="{{ $w4['persen'] == 100 ? 'score-ind-100' : ($w4['persen'] >= 50 ? 'score-ind-partial' : 'score-ind-0') }}">{{ $w4['persen'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="font-style:italic;color:#94a3b8;">Belum ada anggota terdaftar di departemen ini</td>
                    </tr>
                @endforelse
            @endforeach
        </tbody>
    </table>

    {{-- LEGENDA INDIKATOR --}}
    <div class="legend-box">
        <span style="margin-right:8px;color:#0f172a;">Indikator Target:</span>
        <span class="legend-item" style="background:#dcfce7;color:#15803d;border:1px solid #86efac;">100% (Tercapai - Target 2 Laporan/Minggu)</span>
        <span class="legend-item" style="background:#fef08a;color:#854d0e;border:1px solid #fde047;">50% - 99% (Sebagian)</span>
        <span class="legend-item" style="background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;">0% (Belum Mengirim)</span>
    </div>
</body>
</html>
