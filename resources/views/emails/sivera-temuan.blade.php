<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notifikasi SIVERA #{{ $temuan->id }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background:#f4f6f9; margin:0; padding:20px; color:#333; }
        .card  { max-width:600px; margin:0 auto; background:#fff; border-radius:10px; overflow:hidden; }
        .hdr   { background:linear-gradient(135deg,#1565c0 0%,#1e88e5 100%); padding:24px; text-align:center; color:#fff; }
        .hdr h1{ margin:0; font-size:21px; font-weight:700; }
        .hdr p { margin:4px 0 0; font-size:12px; opacity:.88; }
        .body  { padding:28px; }
        .badge { display:inline-block; padding:5px 13px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; margin-bottom:14px; }
        .b-open   { background:#ffebee; color:#c62828; }
        .b-prog   { background:#fff3e0; color:#ef6c00; }
        .b-pend   { background:#e3f2fd; color:#1565c0; }
        .b-closed { background:#e8f5e9; color:#2e7d32; }
        .tbl { width:100%; border-collapse:collapse; margin:16px 0; font-size:13.5px; }
        .tbl td   { padding:9px 11px; border-bottom:1px solid #edf2f7; }
        .tbl .lbl { font-weight:600; color:#64748b; width:36%; }
        .tbl .val { color:#1e293b; }
        .callout  { background:#e8f4fd; border-left:4px solid #1565c0; padding:12px 16px; border-radius:4px; font-size:13.5px; margin:16px 0; line-height:1.6; }
        .btn      { display:inline-block; background:#1565c0; color:#fff !important; padding:11px 22px; border-radius:6px; text-decoration:none; font-weight:600; font-size:13px; margin-top:18px; }
        .footer   { background:#f8fafc; padding:14px; text-align:center; font-size:11px; color:#94a3b8; border-top:1px solid #e2e8f0; line-height:1.6; }
    </style>
</head>
<body>
<div class="card">
    <div class="hdr">
        <h1>SIVERA &mdash; Audit Internal</h1>
        <p>Sistem Verifikasi &amp; Pelaporan Temuan Audit &bull; {{ config('app.name') }}</p>
    </div>

    <div class="body">
        @php
            $bdgClass = match($temuan->status) {
                'open'              => 'b-open',
                'in_progress'       => 'b-prog',
                'closed_pending_qa' => 'b-pend',
                'closed_acc'        => 'b-closed',
                default             => 'b-open',
            };
            $bdgText = match($temuan->status) {
                'open'              => 'Open — Menunggu Aksi',
                'in_progress'       => 'In Progress — Rencana Aksi Dibuat',
                'closed_pending_qa' => 'Pending QA — Menunggu Verifikasi',
                'closed_acc'        => 'Closed ACC — Selesai',
                default             => strtoupper($temuan->status),
            };
            $greeting = $recipientName ?? 'Tim SIVERA';
            $intro    = match($type) {
                'baru'         => 'Anda ditunjuk sebagai <strong>Person in Charge (PIC)</strong> untuk menindaklanjuti temuan audit internal berikut. Mohon segera ambil tindakan.',
                'tindaklanjut' => 'PIC telah mengisi rencana aksi dan menetapkan target tanggal penyelesaian untuk temuan berikut.',
                'bukti'        => 'PIC telah mengunggah bukti perbaikan. Mohon lakukan verifikasi segera.',
                'closed'       => 'Temuan audit internal berikut telah diverifikasi dan <strong>dinyatakan selesai (Closed ACC)</strong> oleh QA.',
                default        => 'Terdapat pembaruan status pada temuan audit internal berikut.',
            };
        @endphp

        <span class="badge {{ $bdgClass }}">{{ $bdgText }}</span>

        <p style="font-size:15px;line-height:1.55;margin-top:0;">
            Yth. <strong>{{ $greeting }}</strong>,<br>
            {!! $intro !!}
        </p>

        <table class="tbl">
            <tr><td class="lbl">ID Temuan</td>    <td class="val"><strong>#{{ $temuan->id }}</strong></td></tr>
            <tr><td class="lbl">Tanggal</td>       <td class="val">{{ $temuan->tanggal_temuan?->format('d F Y') ?? '-' }}</td></tr>
            <tr><td class="lbl">Departemen</td>    <td class="val">{{ $temuan->departemen?->nama_departemen ?? '-' }}</td></tr>
            <tr><td class="lbl">Sub Area</td>      <td class="val">{{ $temuan->sub_area }}{{ $temuan->detail_sub_area ? ' (' . $temuan->detail_sub_area . ')' : '' }}</td></tr>
            <tr><td class="lbl">Klausul PRP</td>   <td class="val">{{ $temuan->klausul?->kode_klausul ?? '-' }} &mdash; {{ $temuan->klausul?->nama_klausul ?? '-' }}</td></tr>
            <tr><td class="lbl">Pelapor</td>       <td class="val">{{ $temuan->pelapor?->name ?? '-' }}</td></tr>
            <tr><td class="lbl">PIC</td>           <td class="val">{{ $temuan->pic?->name ?? '-' }}</td></tr>
            @if($type === 'tindaklanjut' && $temuan->tindakLanjut?->due_date)
            <tr><td class="lbl">Target Selesai</td><td class="val"><strong>{{ $temuan->tindakLanjut->due_date->format('d F Y') }}</strong></td></tr>
            @endif
            @if($type === 'closed' && $temuan->tindakLanjut?->tanggal_acc)
            <tr><td class="lbl">Tanggal ACC</td>   <td class="val">{{ $temuan->tindakLanjut->tanggal_acc->format('d F Y') }}</td></tr>
            @endif
        </table>

        @if($type === 'baru')
        <div class="callout">
            &#128276; <strong>Aksi diperlukan:</strong> Login ke SIVERA dan isi rencana tindak lanjut serta target tanggal penyelesaian pada halaman detail temuan.
        </div>
        @elseif($type === 'bukti')
        <div class="callout">
            &#9989; Bukti perbaikan telah dikirim oleh PIC. Silakan buka detail temuan dan verifikasi apakah temuan dapat ditutup (ACC).
        </div>
        @endif

        <div style="text-align:center;">
            <a href="{{ route('temuan.detail', $temuan->id) }}" class="btn">&#128065; Lihat Detail Temuan #{{ $temuan->id }}</a>
        </div>
    </div>

    <div class="footer">
        Email ini dikirim otomatis oleh Sistem SIVERA &bull; {{ now()->format('d M Y, H:i') }} WIB<br>
        Harap tidak membalas email ini. Jika ada pertanyaan, hubungi Tim QA secara langsung.
    </div>
</div>
</body>
</html>
