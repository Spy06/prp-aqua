<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Temuan SIVERA — {{ $filterLabel }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9.5px; color: #1f2937; background: #fff; padding: 20px; }
        .header { border-bottom: 3px solid #7c3aed; padding-bottom: 12px; margin-bottom: 16px; }
        .company-name { font-size: 15px; font-weight: bold; color: #6d28d9; }
        .doc-title { font-size: 13px; font-weight: bold; color: #4c1d95; text-align: right; }
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
        .badge-progress { background: #e3f2fd; color: #1565c0; }
        .badge-pending { background: #f3e5f5; color: #6a1b9a; }
        .badge-closed { background: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width:100%;">
            <tr>
                <td>
                    <div class="company-name">PRP PLANT AQUA</div>
                    <div style="font-size:10px;font-weight:bold;color:#4b5563;margin-top:2px;">SIVERA — Sistem Verifikasi & Pelaporan Temuan Auditee</div>
                </td>
                <td>
                    <div class="doc-title">LAPORAN DAFTAR TEMUAN</div>
                    <div class="doc-meta">Periode: {{ $filterLabel }} | Diunduh: {{ now()->translatedFormat('d F Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Stat Cards --}}
    <table class="stat-table">
        <tr>
            <td>
                <div class="stat-val" style="color:#6d28d9;">{{ $total }}</div>
                <div class="stat-lbl">Total Temuan</div>
            </td>
            <td>
                <div class="stat-val" style="color:#e65100;">{{ $open }}</div>
                <div class="stat-lbl">Status Open</div>
            </td>
            <td>
                <div class="stat-val" style="color:#1565c0;">{{ $inProgress }}</div>
                <div class="stat-lbl">In Progress</div>
            </td>
            <td>
                <div class="stat-val" style="color:#6a1b9a;">{{ $pendingQa }}</div>
                <div class="stat-lbl">Pending QA</div>
            </td>
            <td>
                <div class="stat-val" style="color:#2e7d32;">{{ $closedAcc }}</div>
                <div class="stat-lbl">Closed ACC</div>
            </td>
        </tr>
    </table>

    {{-- Detail Table --}}
    <div style="font-size:10px;font-weight:bold;color:#6d28d9;margin-bottom:6px;text-transform:uppercase;">Rincian Data Temuan Audit</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;">ID</th>
                <th style="width:10%;">Tanggal</th>
                <th style="width:18%;">Departemen & Area</th>
                <th style="width:18%;">Pelapor & PIC</th>
                <th style="width:23%;">Klausul & Deskripsi Temuan</th>
                <th style="width:26%;">Status & Rencana Aksi (Action)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($temuans as $t)
                @php
                    $badgeClass = match($t->status) {
                        'open'              => 'badge-open',
                        'in_progress'       => 'badge-progress',
                        'closed_pending_qa' => 'badge-pending',
                        'closed_acc'        => 'badge-closed',
                        default             => '',
                    };
                    $badgeText = match($t->status) {
                        'open'              => 'OPEN',
                        'in_progress'       => 'IN PROGRESS',
                        'closed_pending_qa' => 'PENDING QA',
                        'closed_acc'        => 'CLOSED (ACC)',
                        default             => strtoupper($t->status),
                    };
                @endphp
                <tr>
                    <td style="font-weight:bold;color:#6d28d9;">#{{ $t->id }}</td>
                    <td>{{ $t->tanggal_temuan ? $t->tanggal_temuan->format('d/m/Y') : '-' }}</td>
                    <td>
                        <strong>{{ $t->departemen->nama_departemen ?? '-' }}</strong><br>
                        <span style="color:#6b7280;">{{ $t->sub_area ?? '-' }} {{ $t->detail_sub_area ? "({$t->detail_sub_area})" : '' }}</span>
                    </td>
                    <td>
                        <strong>Pelapor:</strong> {{ $t->pelapor->name ?? '-' }}<br>
                        <span style="color:#6b7280;"><strong>PIC:</strong> {{ $t->pic->name ?? '-' }}</span>
                    </td>
                    <td>
                        @if($t->klausul)
                            <strong style="color:#4c1d95;">[{{ $t->klausul->kode_klausul }}] {{ $t->klausul->nama_klausul }}</strong><br>
                        @endif
                        <span style="color:#374151;">{{ $t->deskripsi ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                        @if($t->tindakLanjut && $t->tindakLanjut->action)
                            <div style="font-size:7.5px;color:#4b5563;margin-top:3px;">
                                <strong>Action:</strong> {{ $t->tindakLanjut->action }}
                                @if($t->tindakLanjut->due_date)
                                    <br><span style="color:#6b7280;">Due Date: {{ \Carbon\Carbon::parse($t->tindakLanjut->due_date)->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:16px;color:#6b7280;">Tidak ada data temuan audit.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
