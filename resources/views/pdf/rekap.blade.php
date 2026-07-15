<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Temuan PRP — {{ $periodeLabel }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1f2937;
            background: #fff;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .header-top { display: flex; justify-content: space-between; }
        .company-name { font-size: 16px; font-weight: bold; color: #1e3a8a; }
        .doc-title { font-size: 13px; font-weight: bold; color: #1e3a8a; text-align: right; }
        .doc-meta { font-size: 9px; color: #6b7280; text-align: right; margin-top: 3px; }

        .section { margin-bottom: 18px; }
        .section-title {
            font-size: 10px; font-weight: bold; color: #2563eb;
            text-transform: uppercase; letter-spacing: 0.05em;
            border-bottom: 1px solid #dbeafe;
            padding-bottom: 4px; margin-bottom: 10px;
        }

        .summary-grid {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }
        .summary-card {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .summary-card .val { font-size: 22px; font-weight: bold; }
        .summary-card .lbl { font-size: 9px; color: #6b7280; text-transform: uppercase; }
        .val-total { color: #1f2937; }
        .val-open  { color: #92400e; }
        .val-ip    { color: #1e40af; }
        .val-pqa   { color: #5b21b6; }
        .val-acc   { color: #065f46; }

        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th {
            background: #f3f4f6;
            padding: 6px 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }
        td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
        tr:nth-child(even) td { background: #f9fafb; }
        .status-badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: bold;
        }
        .s-open     { background: #fef3c7; color: #92400e; }
        .s-ip       { background: #dbeafe; color: #1e40af; }
        .s-pqa      { background: #ede9fe; color: #5b21b6; }
        .s-acc      { background: #d1fae5; color: #065f46; }

        .footer {
            margin-top: 30px; padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px; color: #9ca3af;
            display: flex; justify-content: space-between;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-top">
            <div>
                <div class="company-name">Sistem Verifikasi PRP Plant</div>
                <div style="font-size:9px; color:#6b7280;">PT Tirta Investama - Pabrik Cianjur — Rekap Temuan</div>
            </div>
            <div>
                <div class="doc-title">REKAP PERIODE TEMUAN PRP</div>
                <div class="doc-meta">Periode: {{ $periodeLabel }}</div>
                <div class="doc-meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="section">
        <div class="section-title">Ringkasan</div>
        <div class="summary-grid">
            <div class="summary-card"><div class="val val-total">{{ $total }}</div><div class="lbl">Total</div></div>
            <div class="summary-card"><div class="val val-open">{{ $perStatus['open'] }}</div><div class="lbl">Open</div></div>
            <div class="summary-card"><div class="val val-ip">{{ $perStatus['in_progress'] }}</div><div class="lbl">In Progress</div></div>
            <div class="summary-card"><div class="val val-pqa">{{ $perStatus['closed_pending_qa'] }}</div><div class="lbl">Pending QA</div></div>
            <div class="summary-card"><div class="val val-acc">{{ $perStatus['closed_acc'] }}</div><div class="lbl">Closed ACC</div></div>
        </div>
    </div>

    {{-- Per Departemen --}}
    @if($perDepartemen->isNotEmpty())
        <div class="section">
            <div class="section-title">Breakdown per Departemen</div>
            <table>
                <thead>
                    <tr>
                        <th>Departemen</th>
                        <th style="text-align:center">Total</th>
                        <th style="text-align:center">Open</th>
                        <th style="text-align:center">In Progress</th>
                        <th style="text-align:center">Pending QA</th>
                        <th style="text-align:center">Closed ACC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perDepartemen as $row)
                        <tr>
                            <td>{{ $row['nama'] }}</td>
                            <td style="text-align:center; font-weight:bold">{{ $row['total'] }}</td>
                            <td style="text-align:center; color:#92400e">{{ $row['open'] }}</td>
                            <td style="text-align:center; color:#1e40af">{{ $row['in_progress'] }}</td>
                            <td style="text-align:center; color:#5b21b6">{{ $row['closed_pending_qa'] }}</td>
                            <td style="text-align:center; color:#065f46">{{ $row['closed_acc'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Detail Daftar Temuan --}}
    @if($temuans->isNotEmpty())
        <div class="section">
            <div class="section-title">Daftar Temuan</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Departemen</th>
                        <th>Sub Area</th>
                        <th>PIC</th>
                        <th>Status</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($temuans as $t)
                        @php
                            $statusClass = match($t->status) {
                                'open'              => 's-open',
                                'in_progress'       => 's-ip',
                                'closed_pending_qa' => 's-pqa',
                                'closed_acc'        => 's-acc',
                                default             => '',
                            };
                            $statusLabel = match($t->status) {
                                'open'              => 'Open',
                                'in_progress'       => 'In Progress',
                                'closed_pending_qa' => 'Pending QA',
                                'closed_acc'        => 'Closed ACC',
                                default             => $t->status,
                            };
                        @endphp
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td>{{ $t->tanggal_temuan->format('d/m/Y') }}</td>
                            <td>{{ $t->departemen->nama_departemen ?? '-' }}</td>
                            <td>{{ $t->sub_area }}</td>
                            <td>{{ $t->pic->name ?? '-' }}</td>
                            <td><span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            <td>{{ $t->tindakLanjut?->due_date?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <span>Sistem Verifikasi PRP Plant</span>
        <span>Dicetak otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}</span>
    </div>
</body>
</html>
