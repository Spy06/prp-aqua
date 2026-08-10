<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @verbatim
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Daftar Observasi BOSQ</x:Name>
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
        .header-sub { font-size: 11pt; font-weight: bold; color: #7c3aed; border: none; text-align: left; }
        .header-meta { font-size: 9.5pt; color: #64748b; border: none; text-align: left; }
        
        .section-header { font-size: 11pt; font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: left; padding: 8px 12px; border: 1px solid #0f172a; }
        
        .bg-dark-header { background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; }
        .row-total { background-color: #e2e8f0; font-weight: bold; color: #0f172a; }
        .text-center { text-align: center; }
        
        /* Status & Dampak badges for Excel */
        .badge-open { background-color: #ffedd5; color: #9a3412; font-weight: bold; text-align: center; }
        .badge-closed { background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center; }
        .badge-positif { background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center; }
        .badge-negatif { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; }
        
        .badge-resiko-rendah { background-color: #f1f5f9; color: #475569; font-weight: bold; text-align: center; }
        .badge-resiko-sedang { background-color: #fef08a; color: #854d0e; font-weight: bold; text-align: center; }
        .badge-resiko-tinggi { background-color: #ffedd5; color: #c2410c; font-weight: bold; text-align: center; }
        .badge-resiko-sangat_tinggi { background-color: #fee2e2; color: #b91c1c; font-weight: bold; text-align: center; }
        
        .empty-row { border: none; height: 16px; }
    </style>
</head>
<body>

    {{-- Header Title --}}
    <table>
        <tr>
            <td colspan="18" class="header-title">PRP PLANT AQUA — BOS'Q (Behavior Observation System Quality)</td>
        </tr>
        <tr>
            <td colspan="18" class="header-sub">LAPORAN RINCIAN DAFTAR OBSERVASI PERILAKU MUTU & PRASYARAT (BQA)</td>
        </tr>
        <tr>
            <td colspan="18" class="header-meta">Periode Laporan: <strong>{{ $periodeLabel }}</strong> &nbsp;|&nbsp; Tanggal Unduh: {{ now()->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>
        <tr class="empty-row"><td colspan="18" class="empty-row"></td></tr>
    </table>

    {{-- TABEL 1: RINGKASAN PER DEPARTEMEN --}}
    <table>
        <thead>
            <tr>
                <th colspan="6" class="section-header">1. RINGKASAN OBSERVASI PER DEPARTEMEN</th>
            </tr>
            <tr class="bg-dark-header">
                <th style="text-align:left;width:30%;">Nama Departemen</th>
                <th style="width:14%;text-align:center;">Total Observasi</th>
                <th style="width:14%;text-align:center;background-color:#1e40af;color:#ffffff;">Dampak Positif</th>
                <th style="width:14%;text-align:center;background-color:#991b1b;color:#ffffff;">Dampak Negatif</th>
                <th style="width:14%;text-align:center;background-color:#9a3412;color:#ffffff;">Status Open</th>
                <th style="width:14%;text-align:center;background-color:#166534;color:#ffffff;">Status Closed</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perDepartemen as $dept)
                <tr>
                    <td style="font-weight:bold;color:#0f172a;">{{ $dept['nama'] }}</td>
                    <td class="text-center" style="font-weight:bold;">{{ $dept['total'] }}</td>
                    <td class="text-center" style="color:#1e40af;font-weight:bold;">{{ $dept['positif'] }}</td>
                    <td class="text-center" style="color:#991b1b;font-weight:bold;">{{ $dept['negatif'] }}</td>
                    <td class="text-center" style="color:#9a3412;font-weight:bold;">{{ $dept['open'] }}</td>
                    <td class="text-center" style="color:#166534;font-weight:bold;">{{ $dept['closed'] }}</td>
                </tr>
            @endforeach
            <tr class="row-total">
                <td>TOTAL SELURUH DEPARTEMEN</td>
                <td class="text-center">{{ $total }}</td>
                <td class="text-center">{{ $perDampak['positif'] }}</td>
                <td class="text-center">{{ $perDampak['negatif'] }}</td>
                <td class="text-center">{{ $perStatus['open'] }}</td>
                <td class="text-center">{{ $perStatus['closed'] }}</td>
            </tr>
        </tbody>
    </table>

    <table><tr class="empty-row"><td colspan="18" class="empty-row"></td></tr></table>

    {{-- TABEL 2: RINCIAN DAFTAR OBSERVASI --}}
    <table>
        <thead>
            <tr>
                <th colspan="18" class="section-header">2. RINCIAN DATA OBSERVASI BEHAVIOR MUTU (BQA)</th>
            </tr>
            <tr class="bg-dark-header">
                <th style="width:4%;">ID</th>
                <th style="width:9%;">Tgl Observasi</th>
                <th style="width:12%;">Departemen</th>
                <th style="width:10%;">Sub Area</th>
                <th style="width:10%;">Detail Sub Area</th>
                <th style="width:8%;">NIK Observer</th>
                <th style="width:12%;">Observer (Pelapor)</th>
                <th style="width:8%;">NIK Auditee</th>
                <th style="width:12%;">Auditee</th>
                <th style="width:12%;">Elemen QFS</th>
                <th style="width:20%;">Temuan BQA</th>
                <th style="width:9%;">Tingkat Risiko</th>
                <th style="width:9%;">Dampak</th>
                <th style="width:8%;">Status</th>
                <th style="width:20%;">Rencana Aksi (Action)</th>
                <th style="width:9%;">Due Date</th>
                <th style="width:9%;">Tgl ACC QA</th>
                <th style="width:15%;">Catatan QA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($temuans as $t)
                @php
                    $tl = $t->tindakLanjut;
                    $isClosed = in_array($t->status, ['closed', 'closed_acc']);
                    $resikoClass = 'badge-resiko-' . strtolower($t->tingkat_resiko ?? 'rendah');
                @endphp
                <tr>
                    <td class="text-center" style="font-weight:bold;">#{{ $t->id }}</td>
                    <td class="text-center">{{ $t->tanggal_temuan ? $t->tanggal_temuan->format('d/m/Y') : '-' }}</td>
                    <td style="font-weight:bold;">{{ $t->departemen->nama_departemen ?? '-' }}</td>
                    <td>{{ $t->subArea->nama_sub_area ?? '-' }}</td>
                    <td>{{ $t->detail_sub_area ?? '-' }}</td>
                    <td class="text-center">{{ $t->pelapor->nik ?? '-' }}</td>
                    <td>{{ $t->pelapor->name ?? '-' }}</td>
                    <td class="text-center">{{ $t->auditee->nik ?? '-' }}</td>
                    <td>{{ $t->auditee->name ?? '-' }}</td>
                    <td style="font-weight:bold;color:#4c1d95;">{{ $t->elemenQfs->nama_elemen ?? '-' }}</td>
                    <td>{{ $t->temuan_bqa ?? '-' }}</td>
                    <td class="{{ $resikoClass }}">{{ str_replace('_', ' ', strtoupper($t->tingkat_resiko ?? 'RENDAH')) }}</td>
                    <td class="{{ $t->dampak_temuan === 'negatif' ? 'badge-negatif' : 'badge-positif' }}">{{ strtoupper($t->dampak_temuan ?? 'POSITIF') }}</td>
                    <td class="{{ $isClosed ? 'badge-closed' : 'badge-open' }}">{{ $isClosed ? 'CLOSED' : 'OPEN' }}</td>
                    <td>{{ $tl?->action ?? '-' }}</td>
                    <td class="text-center">{{ $tl?->due_date ? \Carbon\Carbon::parse($tl->due_date)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $tl?->tanggal_acc ? \Carbon\Carbon::parse($tl->tanggal_acc)->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $tl?->catatan_qa ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" class="text-center" style="padding:20px;font-style:italic;color:#64748b;">
                        Tidak ada data observasi pada periode yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
