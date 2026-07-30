<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Temuan PRP #{{ $temuan->id }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #fff;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
            text-align: right;
        }
        .doc-meta {
            font-size: 10px;
            color: #6b7280;
            text-align: right;
            margin-top: 3px;
        }
        .section {
            margin-bottom: 18px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #dbeafe;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }
        table.info {
            width: 100%;
            border-collapse: collapse;
        }
        table.info td {
            padding: 5px 8px;
            vertical-align: top;
        }
        table.info td:first-child {
            width: 160px;
            color: #6b7280;
            font-weight: bold;
        }
        table.info tr:nth-child(even) td {
            background: #f9fafb;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-open              { background: #fef3c7; color: #92400e; }
        .status-in_progress       { background: #dbeafe; color: #1e40af; }
        .status-closed_pending_qa { background: #ede9fe; color: #5b21b6; }
        .status-closed_acc        { background: #d1fae5; color: #065f46; }
        .foto-box {
            margin-top: 8px;
            text-align: center;
        }
        .foto-box img {
            max-width: 100%;
            max-height: 200px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #9ca3af;
            display: flex;
            justify-content: space-between;
        }
        .catatan-qa {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 4px;
            padding: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="header-top">
            <div>
                <div class="company-name">Sistem Verifikasi PRP Plant</div>
                <div style="font-size:10px; color:#6b7280;">PT Tirta Investama - Pabrik Cianjur</div>
            </div>
            <div>
                <div class="doc-title">LAPORAN TEMUAN #{{ $temuan->id }}</div>
                <div class="doc-meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Informasi Temuan --}}
    <div class="section">
        <div class="section-title">Informasi Temuan</div>
        <table class="info">
            <tr>
                <td>ID Temuan</td>
                <td>#{{ $temuan->id }}</td>
            </tr>
            <tr>
                <td>Tanggal Temuan</td>
                <td>{{ $temuan->tanggal_temuan->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>{{ $temuan->departemen->nama_departemen ?? '-' }}</td>
            </tr>
            <tr>
                <td>Sub Area</td>
                <td>{{ $temuan->sub_area }}</td>
            </tr>
            <tr>
                <td>Klausul PRP</td>
                <td>{{ $temuan->klausul ? $temuan->klausul->kode_klausul . ' — ' . $temuan->klausul->nama_klausul : '-' }}</td>
            </tr>
            <tr>
                <td>Pelapor</td>
                <td>{{ $temuan->pelapor->name ?? '-' }} ({{ $temuan->pelapor->karyawan->departemen->nama_departemen ?? 'Tanpa Departemen' }})</td>
            </tr>
            <tr>
                <td>PIC</td>
                <td>{{ $temuan->pic->name ?? '-' }} ({{ $temuan->pic->karyawan->departemen->nama_departemen ?? 'Tanpa Departemen' }})</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <span class="status-badge status-{{ $temuan->status }}">
                        {{ match($temuan->status) {
                            'open'              => 'Open',
                            'in_progress'       => 'In Progress',
                            'closed_pending_qa' => 'Closed Pending QA',
                            'closed_acc'        => 'Closed — Disetujui QA',
                            default             => $temuan->status,
                        } }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Deskripsi Temuan</td>
                <td>{{ $temuan->deskripsi }}</td>
            </tr>
            @if($temuan->saran)
            <tr>
                <td>Saran & Masukan</td>
                <td>{{ $temuan->saran }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- Foto Temuan --}}
    @if($temuan->foto_temuan_path)
        <div class="section">
            <div class="section-title">Foto Temuan</div>
            <div class="foto-box">
                <img src="{{ $fotoTemuanUrl }}" alt="Foto Temuan" />
            </div>
        </div>
    @endif

    {{-- Tindak Lanjut --}}
    @if($tl = $temuan->tindakLanjut)
        <div class="section">
            <div class="section-title">Tindak Lanjut PIC</div>
            <table class="info">
                <tr>
                    <td>Tindakan Perbaikan</td>
                    <td>{{ $tl->action ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Due Date</td>
                    <td>{{ $tl->due_date ? $tl->due_date->format('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td>Status Tindak Lanjut</td>
                    <td>{{ $tl->status }}</td>
                </tr>
                @if($tl->tanggal_acc)
                    <tr>
                        <td>Tanggal ACC QA</td>
                        <td>{{ $tl->tanggal_acc->format('d F Y') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        {{-- Foto & Dokumen Bukti Tindak Lanjut --}}
        @if((isset($fotoBuktiUrls) && count($fotoBuktiUrls) > 0) || (isset($docBuktiFiles) && count($docBuktiFiles) > 0))
            <div class="section" style="page-break-inside: avoid;">
                <div class="section-title">Foto & Dokumen Bukti Tindak Lanjut ({{ count($fotoBuktiUrls) + count($docBuktiFiles) }} File)</div>
                
                @if(count($fotoBuktiUrls) > 0)
                    <div style="margin-top: 8px;">
                        @foreach($fotoBuktiUrls as $idx => $bUrl)
                            <div class="foto-box" style="margin-bottom: 14px; page-break-inside: avoid;">
                                @if(count($fotoBuktiUrls) > 1)
                                    <div style="font-size: 10px; font-weight: bold; color: #374151; margin-bottom: 4px; text-align: left;">
                                        Foto Bukti Pengerjaan #{{ $idx + 1 }}
                                    </div>
                                @endif
                                <img src="{{ $bUrl }}" alt="Foto Bukti #{{ $idx + 1 }}" style="max-width: 100%; max-height: 260px; border: 1px solid #e5e7eb; border-radius: 4px;" />
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(count($docBuktiFiles) > 0)
                    <div style="margin-top: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px; page-break-inside: avoid;">
                        <div style="font-weight: bold; color: #1e3a8a; margin-bottom: 6px; font-size: 10px;">Dokumen Lampiran Bukti:</div>
                        @foreach($docBuktiFiles as $doc)
                            <div style="font-size: 10px; color: #334155; padding: 3px 0;">
                                📄 <strong>{{ $doc['name'] }}</strong> (File {{ $doc['ext'] }})
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Catatan QA --}}
        @if($tl->catatan_qa)
            <div class="section">
                <div class="section-title">Catatan QA</div>
                <div class="catatan-qa">{{ $tl->catatan_qa }}</div>
            </div>
        @endif
    @endif

    {{-- Footer --}}
    <div class="footer">
        <span>Dokumen ini dicetak otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}</span>
    </div>
</body>
</html>
