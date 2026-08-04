<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Rekap Kepatuhan BQA</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        table { border-collapse: collapse; width: 100%; font-family: 'Segoe UI', Arial, sans-serif; font-size: 11pt; }
        th, td { border: 1px solid #94a3b8; padding: 8px 12px; text-align: center; vertical-align: middle; }
        
        .header-title { font-size: 16pt; font-weight: bold; color: #0f172a; text-align: left; border: none; }
        .header-sub { font-size: 11pt; color: #475569; text-align: left; border: none; }
        
        .section-header { font-size: 12pt; font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: left; padding: 10px; border: 1px solid #0f172a; }
        
        .bg-dark-header { background-color: #0f172a; color: #ffffff; font-weight: bold; }
        .bg-sub-header { background-color: #1e293b; color: #38bdf8; font-weight: bold; }
        .bg-slate-header { background-color: #334155; color: #ffffff; font-weight: bold; }
        .bg-gray-header { background-color: #475569; color: #ffffff; font-weight: bold; }
        
        .dept-row { background-color: #cbd5e1; color: #0f172a; font-weight: bold; text-align: left; text-transform: uppercase; }
        .member-name { text-align: left; font-weight: bold; color: #1e293b; }
        
        /* Summary table cell colors */
        .sum-100 { background-color: #334155; color: #ffffff; font-weight: bold; }
        .sum-partial { background-color: #ffffff; color: #0f172a; font-weight: bold; }
        .sum-0 { background-color: #0f172a; color: #ffffff; font-weight: bold; }
        
        /* Matrix detail cell colors */
        .cell-100 { background-color: #dcfce7; color: #15803d; font-weight: bold; }
        .cell-partial { background-color: #fef08a; color: #854d0e; font-weight: bold; }
        .cell-0 { background-color: #f1f5f9; color: #64748b; font-weight: bold; }
        
        .empty-row { border: none; height: 18px; }
        .legend-title { font-weight: bold; color: #0f172a; text-align: left; border: none; }
    </style>
</head>
<body>

    <table>
        <tr>
            <td colspan="5" class="header-title">PRP PLANT AQUA — BOS'Q (Behavior Observation System Quality)</td>
        </tr>
        <tr>
            <td colspan="5" class="header-sub">REKAP KEPATUHAN TARGET OBSERVASI BQA — Periode: {{ $monthName }}</td>
        </tr>
        <tr class="empty-row"><td colspan="5" class="empty-row"></td></tr>
    </table>

    {{-- TABEL 1: REKAP KEPATUHAN PER DEPARTEMEN --}}
    <table>
        <thead>
            <tr>
                <th colspan="5" class="section-header">1. REKAP KEPATUHAN PER DEPARTEMEN</th>
            </tr>
            <tr class="bg-dark-header">
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
                    <td class="{{ $p1 == 100 ? 'sum-100' : ($p1 >= 50 ? 'sum-partial' : 'sum-0') }}">{{ $p1 }}%</td>

                    @php $p2 = $ds['scores']['w2']['persen']; @endphp
                    <td class="{{ $p2 == 100 ? 'sum-100' : ($p2 >= 50 ? 'sum-partial' : 'sum-0') }}">{{ $p2 }}%</td>

                    @php $p3 = $ds['scores']['w3']['persen']; @endphp
                    <td class="{{ $p3 == 100 ? 'sum-100' : ($p3 >= 50 ? 'sum-partial' : 'sum-0') }}">{{ $p3 }}%</td>

                    @php $p4 = $ds['scores']['w4']['persen']; @endphp
                    <td class="{{ $p4 == 100 ? 'sum-100' : ($p4 >= 50 ? 'sum-partial' : 'sum-0') }}">{{ $p4 }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table><tr class="empty-row"><td colspan="5" class="empty-row"></td></tr></table>

    {{-- TABEL 2: DETAIL PENCAPAIAN BQA PER ANGGOTA DEPARTEMEN --}}
    <table>
        <thead>
            <tr>
                <th colspan="5" class="section-header">2. DETAIL PENCAPAIAN BQA PER ANGGOTA DEPARTEMEN</th>
            </tr>
            <tr class="bg-dark-header">
                <th style="text-align:left;width:30%;">PENCAPAIAN BQA (Sum of % in WEEK)</th>
                <th colspan="4" class="bg-sub-header">{{ $monthName }}</th>
            </tr>
            <tr class="bg-slate-header">
                <th style="text-align:left;">Row Labels</th>
                <th>{{ $weeks['w1']['label'] }}</th>
                <th>{{ $weeks['w2']['label'] }}</th>
                <th>{{ $weeks['w3']['label'] }}</th>
                <th>{{ $weeks['w4']['label'] }}</th>
            </tr>
            <tr class="bg-gray-header">
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
                    <td colspan="5" class="dept-row">{{ $deptGroup['nama_departemen'] }}</td>
                </tr>
                @forelse($deptGroup['members'] as $m)
                    <tr>
                        <td class="member-name">{{ $m['nama'] }}</td>
                        @php $w1 = $m['scores']['w1']; @endphp
                        <td class="{{ $w1['persen'] == 100 ? 'cell-100' : ($w1['persen'] >= 50 ? 'cell-partial' : 'cell-0') }}">{{ $w1['persen'] }}%</td>

                        @php $w2 = $m['scores']['w2']; @endphp
                        <td class="{{ $w2['persen'] == 100 ? 'cell-100' : ($w2['persen'] >= 50 ? 'cell-partial' : 'cell-0') }}">{{ $w2['persen'] }}%</td>

                        @php $w3 = $m['scores']['w3']; @endphp
                        <td class="{{ $w3['persen'] == 100 ? 'cell-100' : ($w3['persen'] >= 50 ? 'cell-partial' : 'cell-0') }}">{{ $w3['persen'] }}%</td>

                        @php $w4 = $m['scores']['w4']; @endphp
                        <td class="{{ $w4['persen'] == 100 ? 'cell-100' : ($w4['persen'] >= 50 ? 'cell-partial' : 'cell-0') }}">{{ $w4['persen'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="font-style:italic;color:#94a3b8;">Belum ada anggota terdaftar di departemen ini</td>
                    </tr>
                @endforelse
            @endforeach
        </tbody>
    </table>

    <table><tr class="empty-row"><td colspan="5" class="empty-row"></td></tr></table>

    {{-- LEGENDA INDIKATOR --}}
    <table>
        <tr>
            <td colspan="5" class="legend-title">
                <strong>Indikator Target:</strong> &nbsp;&nbsp;
                <span class="cell-100" style="padding:4px 8px;">100% (Tercapai - Target 2 Laporan/Minggu)</span> &nbsp;&nbsp;
                <span class="cell-partial" style="padding:4px 8px;">50% - 99% (Sebagian)</span> &nbsp;&nbsp;
                <span class="cell-0" style="padding:4px 8px;">0% (Belum Mengirim)</span>
            </td>
        </tr>
    </table>

</body>
</html>
