<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi SIVERA</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333; }
        .email-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .email-header { background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%); padding: 24px; text-align: center; color: #ffffff; }
        .email-header h1 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
        .email-header p { margin: 4px 0 0; font-size: 13px; opacity: 0.9; }
        .email-body { padding: 28px; }
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 16px; }
        .badge-open { background-color: #ffebee; color: #c62828; }
        .badge-closed { background-color: #e8f5e9; color: #2e7d32; }
        .badge-progress { background-color: #fff3e0; color: #ef6c00; }
        .badge-pending { background-color: #e3f2fd; color: #1565c0; }
        .info-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .info-table td { padding: 10px 12px; border-bottom: 1px solid #edf2f7; font-size: 13.5px; }
        .info-table td.label { font-weight: 600; color: #64748b; width: 35%; }
        .info-table td.value { color: #1e293b; font-weight: 500; }
        .btn-action { display: inline-block; background-color: #1565c0; color: #ffffff !important; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; margin-top: 20px; text-align: center; }
        .email-footer { background-color: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="email-header">
            <h1>SIVERA — Audit System</h1>
            <p>Sistem Verifikasi & Pelaporan Temuan Audit Internal</p>
        </div>

        <div class="email-body">
            @php
                $statusClass = match($temuan->status) {
                    'open' => 'badge-open',
                    'in_progress' => 'badge-progress',
                    'closed_pending_qa' => 'badge-pending',
                    'closed_acc' => 'badge-closed',
                    default => 'badge-open'
                };
                $statusText = match($temuan->status) {
                    'open' => 'OPEN (Menunggu Aksi)',
                    'in_progress' => 'IN PROGRESS (Rencana Aksi)',
                    'closed_pending_qa' => 'CLOSED (Menunggu Verifikasi QA)',
                    'closed_acc' => 'CLOSED (ACC BY QA)',
                    default => strtoupper($temuan->status)
                };
            @endphp

            <div class="status-badge {{ $statusClass }}">{{ $statusText }}</div>

            <p style="font-size: 15px; line-height: 1.5; margin-top: 0;">
                Halo <strong>{{ $type === 'baru' ? ($temuan->pic?->name ?? 'PIC') : ($temuan->pelapor?->name ?? 'User') }}</strong>,
                <br>
                Berikut adalah pemberitahuan pembaruan data temuan audit internal SIVERA:
            </p>

            <table class="info-table">
                <tr>
                    <td class="label">ID Temuan</td>
                    <td class="value">#{{ $temuan->id }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Temuan</td>
                    <td class="value">{{ $temuan->tanggal_temuan ? $temuan->tanggal_temuan->format('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Departemen</td>
                    <td class="value">{{ $temuan->departemen?->nama_departemen ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Sub Area</td>
                    <td class="value">{{ $temuan->sub_area }} @if($temuan->detail_sub_area) ({{ $temuan->detail_sub_area }}) @endif</td>
                </tr>
                <tr>
                    <td class="label">Klausul PRP</td>
                    <td class="value">{{ $temuan->klausul?->kode_klausul ?? '-' }} - {{ $temuan->klausul?->nama_klausul ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Pelapor</td>
                    <td class="value">{{ $temuan->pelapor?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">PIC Ditunjuk</td>
                    <td class="value">{{ $temuan->pic?->name ?? '-' }}</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ route('temuan.detail', $temuan->id) }}" class="btn-action">Lihat Detail Temuan SIVERA</a>
            </div>
        </div>

        <div class="email-footer">
            Email ini dikirim secara otomatis oleh Sistem SIVERA. Harap tidak membalas email ini.
        </div>
    </div>
</body>
</html>
