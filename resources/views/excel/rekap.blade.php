<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @verbatim
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Rekap Temuan Audit SIVERA</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    @endverbatim
    <style>
        table { border-collapse: collapse; width: 100%; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 10px; text-align: left; vertical-align: middle; }
        
        .header-title { font-size: 15pt; font-weight: bold; color: #0f172a; border: none; text-align: left; }
        .header-sub { font-size: 11pt; font-weight: bold; color: #2563eb; border: none; text-align: left; }
        .header-meta { font-size: 9.5pt; color: #64748b; border: none; text-align: left; }
        
        .section-header { font-size: 11pt; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: left; padding: 8px 12px; border: 1px solid #1e293b; }
        
        .bg-dark-header { background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; }
        .bg-sub-header { background-color: #334155; color: #ffffff; font-weight: bold; }
        
        .row-total { background-color: #e2e8f0; font-weight: bold; color: #0f172a; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Status badge cell colors for Excel */
        .status-open { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; }
        .status-progress { background-color: #ffedd5; color: #9a3412; font-weight: bold; text-align: center; }
        .status-pending { background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center; }
        .status-closed { background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center; }
        
        .empty-row { border: none; height: 16px; }
    </style>
</head>
<body>

    {{-- Title Header Block --}}
    <table>
        <tr>
            <td colspan="16" class="header-title">SIVERA — Sistem Verifikasi & Pelaporan Temuan Auditee</td>
        </tr>
        <tr>
            <td colspan="16" class="header-sub">REKAPITULASI TEMUAN AUDIT INTERNAL & KEPATUHAN PRASYARAT (PRP)</td>
        </tr>
        <tr>
            <td colspan="16" class="header-meta">Periode Laporan: <strong>{{ $periodeLabel }}</strong> &nbsp;|&nbsp; Tanggal Unduh: {{ now()->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>
        <tr class="empty-row"><td colspan="16" class="empty-row"></td></tr>
    </table>

    {{-- TABEL 1: RINGKASAN DEPARTEMEN --}}
    <table>
        <thead>
            <tr>
                <th colspan="6" class="section-header">1. RINGKASAN KEPATUHAN PER DEPARTEMEN</th>
            </tr>
            <tr class="bg-dark-header">
                <th style="text-align:left;width:30%;">Nama Departemen</th>
                <th style="width:14%;text-align:center;">Total Temuan</th>
                <th style="width:14%;text-align:center;background-color:#991b1b;color:#ffffff;">Open</th>
                <th style="width:14%;text-align:center;background-color:#9a3412;color:#ffffff;">In Progress</th>
                <th style="width:14%;text-align:center;background-color:#1e40af;color:#ffffff;">Pending QA</th>
                <th style="width:14%;text-align:center;background-color:#166534;color:#ffffff;">Closed (ACC)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perDepartemen as $dept)
                <tr>
                    <td style="font-weight:bold;color:#0f172a;">{{ $dept['nama'] }}</td>
                    <td class="text-center" style="font-weight:bold;">{{ $dept['total'] }}</td>
                    <td class="text-center" style="color:#991b1b;font-weight:bold;">{{ $dept['open'] }}</td>
                    <td class="text-center" style="color:#9a3412;font-weight:bold;">{{ $dept['in_progress'] }}</td>
                    <td class="text-center" style="color:#1e40af;font-weight:bold;">{{ $dept['closed_pending_qa'] }}</td>
                    <td class="text-center" style="color:#166534;font-weight:bold;">{{ $dept['closed_acc'] }}</td>
                </tr>
            @endforeach
            <tr class="row-total">
                <td>TOTAL SELURUH DEPARTEMEN</td>
                <td class="text-center">{{ $total }}</td>
                <td class="text-center">{{ $perStatus['open'] }}</td>
                <td class="text-center">{{ $perStatus['in_progress'] }}</td>
                <td class="text-center">{{ $perStatus['closed_pending_qa'] }}</td>
                <td class="text-center">{{ $perStatus['closed_acc'] }}</td>
            </tr>
        </tbody>
    </table>

    <table><tr class="empty-row"><td colspan="16" class="empty-row"></td></tr></table>

    {{-- TABEL 2: DAFTAR DETAIL TEMUAN --}}
    <table>
        <thead>
            <tr>
                <th colspan="16" class="section-header">2. DAFTAR DETAIL TEMUAN AUDIT INTERNAL</th>
            </tr>
            <tr class="bg-dark-header">
                <th style="width:4%;">ID</th>
                <th style="width:9%;">Tgl Temuan</th>
                <th style="width:12%;">Departemen</th>
                <th style="width:10%;">Sub Area</th>
                <th style="width:8%;">NIK Pelapor</th>
                <th style="width:12%;">Nama Pelapor</th>
                <th style="width:8%;">NIK PIC</th>
                <th style="width:12%;">Nama PIC</th>
                <th style="width:9%;">Status</th>
                <th style="width:12%;">Klausul PRP</th>
                <th style="width:20%;">Deskripsi & Masukan</th>
                <th style="width:20%;">Tindakan Perbaikan</th>
                <th style="width:9%;">Due Date</th>
                <th style="width:7%;">Foto Bukti</th>
                <th style="width:9%;">Tgl ACC QA</th>
                <th style="width:15%;">Catatan QA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($temuans as $t)
                @php
                    $tl = $t->tindakLanjut;
                    $statusClass = match($t->status) {
                        'open'              => 'status-open',
                        'in_progress'       => 'status-progress',
                        'closed_pending_qa' => 'status-pending',
                        'closed_acc'        => 'status-closed',
                        default             => '',
                    };
                    $statusLabel = match($t->status) {
                        'open'              => 'OPEN',
                        'in_progress'       => 'IN PROGRESS',
                        'closed_pending_qa' => 'PENDING QA',
                        'closed_acc'        => 'CLOSED (ACC)',
                        default             => strtoupper($t->status),
                    };
                @endphp
                <tr>
                    <td class="text-center" style="font-weight:bold;">#{{ $t->id }}</td>
                    <td class="text-center">{{ $t->tanggal_temuan ? $t->tanggal_temuan->format('d/m/Y') : '-' }}</td>
                    <td style="font-weight:bold;">{{ $t->departemen->nama_departemen ?? '-' }}</td>
                    <td>
                        {{ $t->sub_area }}
                        @if($t->detail_sub_area) ({{ $t->detail_sub_area }}) @endif
                    </td>
                    <td class="text-center">{{ $t->pelapor->nik ?? '-' }}</td>
                    <td>{{ $t->pelapor->name ?? '-' }}</td>
                    <td class="text-center">{{ $t->pic->nik ?? '-' }}</td>
                    <td>{{ $t->pic->name ?? '-' }}</td>
                    <td class="{{ $statusClass }}">{{ $statusLabel }}</td>
                    <td>{{ $t->klausul ? $t->klausul->kode_klausul . ' - ' . $t->klausul->nama_klausul : '-' }}</td>
                    <td>
                        @if($t->deskripsi) <strong>[Deskripsi]:</strong> {{ $t->deskripsi }}<br/> @endif
                        @if($t->saran) <strong>[Saran]:</strong> {{ $t->saran }} @endif
                    </td>
                    <td>{{ $tl?->action ?? '-' }}</td>
                    <td class="text-center">{{ $tl?->due_date ? $tl->due_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $tl?->foto_bukti_path ? 'Ada' : 'Belum' }}</td>
                    <td class="text-center">{{ $tl?->tanggal_acc ? $tl->tanggal_acc->format('d/m/Y') : '-' }}</td>
                    <td>{{ $tl?->catatan_qa ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center" style="padding:20px;font-style:italic;color:#64748b;">
                        Tidak ada data temuan pada periode yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
