<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Observasi BOS'Q — {{ $filterLabel }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9.5px; color: #1f2937; background: #fff; padding: 20px; }
        .header { border-bottom: 3px solid #1976d2; padding-bottom: 12px; margin-bottom: 16px; }
        .company-name { font-size: 15px; font-weight: bold; color: #1565c0; }
        .doc-title { font-size: 13px; font-weight: bold; color: #1e3a8a; text-align: right; }
        .doc-meta { font-size: 9px; color: #6b7280; text-align: right; margin-top: 3px; }
        
        .stat-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .stat-table td { width: 20%; padding: 8px; border: 1px solid #e5e7eb; text-align: center; border-radius: 4px; }
        .stat-val { font-size: 16px; font-weight: bold; }
        .stat-lbl { font-size: 8px; color: #6b7280; text-transform: uppercase; margin-top: 2px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.data-table th { background: #f3f4f6; padding: 6px 8px; text-align: left; font-size: 8.5px; text-transform: uppercase; color: #4b5563; border-bottom: 1px solid #d1d5db; }
        table.data-table td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; font-size: 8.5px; vertical-align: top; }
        
        .badge { font-weight: bold; padding: 2px 5px; border-radius: 3px; font-size: 7.5px; text-transform: uppercase; display: inline-block; }
        .badge-open { background: #fff3e0; color: #e65100; }
        .badge-closed { background: #e8f5e9; color: #2e7d32; }
        .badge-negatif { background: #ffebee; color: #c62828; }
        .badge-positif { background: #eff6ff; color: #2563eb; }
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
                    <div class="doc-title">LAPORAN OBSERVASI</div>
                    <div class="doc-meta">Periode: {{ $filterLabel }} | Diunduh: {{ now()->translatedFormat('d F Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Stat Cards --}}
    <table class="stat-table">
        <tr>
            <td>
                <div class="stat-val" style="color:#1565c0;">{{ $total }}</div>
                <div class="stat-lbl">Total Observasi</div>
            </td>
            <td>
                <div class="stat-val" style="color:#e65100;">{{ $open }}</div>
                <div class="stat-lbl">Status Open</div>
            </td>
            <td>
                <div class="stat-val" style="color:#2e7d32;">{{ $closed }}</div>
                <div class="stat-lbl">Status Closed</div>
            </td>
            <td>
                <div class="stat-val" style="color:#c62828;">{{ $negatif }}</div>
                <div class="stat-lbl">Dampak Negatif</div>
            </td>
            <td>
                <div class="stat-val" style="color:#2563eb;">{{ $positif }}</div>
                <div class="stat-lbl">Dampak Positif</div>
            </td>
        </tr>
    </table>

    {{-- Detail Table --}}
    <div style="font-size:10px;font-weight:bold;color:#1565c0;margin-bottom:6px;text-transform:uppercase;">Rincian Data Observasi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;">ID</th>
                <th style="width:10%;">Tanggal</th>
                <th style="width:18%;">Dept & Sub Area</th>
                <th style="width:18%;">Observer & Auditee</th>
                <th style="width:20%;">Elemen QFS & Temuan</th>
                <th style="width:15%;">Risiko & Dampak</th>
                <th style="width:14%;">Status & Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($temuans as $t)
                @php $isClosed = in_array($t->status, ['closed', 'closed_acc']); @endphp
                <tr>
                    <td style="font-weight:bold;color:#1976d2;">#{{ $t->id }}</td>
                    <td>{{ $t->tanggal_temuan->format('d/m/Y') }}</td>
                    <td>
                        <strong>{{ $t->departemen->nama_departemen ?? '-' }}</strong><br>
                        <span style="color:#6b7280;">{{ $t->subArea->nama_sub_area ?? '-' }} {{ $t->detail_sub_area ? "({$t->detail_sub_area})" : '' }}</span>
                    </td>
                    <td>
                        <strong>Obs:</strong> {{ $t->pelapor->name ?? '-' }}<br>
                        <span style="color:#6b7280;"><strong>Aud:</strong> {{ $t->auditee->name ?? '-' }}</span>
                    </td>
                    <td>
                        <strong>{{ $t->elemenQfs->nama_elemen ?? '-' }}</strong><br>
                        <span style="color:#374151;">{{ $t->temuan_bqa }}</span>
                    </td>
                    <td>
                        <span class="badge" style="background:#f3f4f6;color:#374151;">{{ str_replace('_', ' ', strtoupper($t->tingkat_resiko)) }}</span><br>
                        <span class="badge {{ $t->dampak_temuan === 'negatif' ? 'badge-negatif' : 'badge-positif' }}">{{ strtoupper($t->dampak_temuan) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $isClosed ? 'badge-closed' : 'badge-open' }}">{{ $isClosed ? 'CLOSED' : 'OPEN' }}</span>
                        @if($t->tindakLanjut && $t->tindakLanjut->action)
                            <div style="font-size:7.5px;color:#6b7280;margin-top:2px;">Act: {{ $t->tindakLanjut->action }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:16px;color:#6b7280;">Tidak ada data observasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
