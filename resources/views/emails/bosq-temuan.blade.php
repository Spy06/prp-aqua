<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi BOS'Q</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .email-card {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            padding: 24px;
            text-align: center;
            color: #ffffff;
        }

        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .email-header p {
            margin: 4px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .email-body {
            padding: 28px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .badge-open {
            background-color: #ffebee;
            color: #c62828;
        }

        .badge-closed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        .info-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #edf2f7;
            font-size: 13.5px;
        }

        .info-table td.label {
            font-weight: 600;
            color: #64748b;
            width: 35%;
        }

        .info-table td.value {
            color: #1e293b;
            font-weight: 500;
        }

        .btn-action {
            display: inline-block;
            background-color: #0d9488;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-top: 20px;
            text-align: center;
        }

        .email-footer {
            background-color: #f8fafc;
            padding: 16px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>
    <div class="email-card">
        <div class="email-header">
            <h1>BOS'Q — Behavior Observasi System</h1>
            <p>Sistem Observasi Perilaku Mutu & Keselamatan</p>
        </div>

        <div class="email-body">
            @php
                $isClosed = in_array($temuan->status, ['closed', 'closed_acc']);
                $statusClass = $isClosed ? 'badge-closed' : 'badge-open';
                $statusText = $isClosed ? 'CLOSED (ACC)' : 'OPEN';
            @endphp

            <div class="status-badge {{ $statusClass }}">{{ $statusText }}</div>

            <p style="font-size: 15px; line-height: 1.5; margin-top: 0;">
                Halo <strong>{{ $recipientName ?? 'User' }}</strong>,
                <br>
                Berikut adalah pemberitahuan data observasi perilaku BOS'Q terbaru:
            </p>

            <table class="info-table">
                <tr>
                    <td class="label">ID Observasi</td>
                    <td class="value">#{{ $temuan->id }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Observasi</td>
                    <td class="value">{{ $temuan->tanggal_temuan ? $temuan->tanggal_temuan->format('d F Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Departemen</td>
                    <td class="value">{{ $temuan->departemen?->nama_departemen ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Sub Area</td>
                    <td class="value">{{ $temuan->subArea?->nama_sub_area ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Dampak Observasi</td>
                    <td class="value">
                        @if($temuan->dampak_temuan === 'positif')
                            <span style="background-color: #e0f2fe; color: #0284c7; padding: 4px 12px; border-radius: 6px; font-weight: 700; font-size: 12.5px; display: inline-block;">Positif</span>
                        @else
                            <span style="background-color: #fee2e2; color: #dc2626; padding: 4px 12px; border-radius: 6px; font-weight: 700; font-size: 12.5px; display: inline-block;">Negatif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Pelapor</td>
                    <td class="value">{{ $temuan->pelapor?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Auditee</td>
                    <td class="value">{{ $temuan->auditee?->name ?? '-' }}</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ route('bosq.temuan.detail', $temuan->id) }}" class="btn-action">Lihat Detail Observasi
                    BOS'Q</a>
            </div>
        </div>

        <div class="email-footer">
            Email ini dikirim secara otomatis oleh Sistem BOS'Q. Harap tidak membalas email ini.
        </div>
    </div>
</body>

</html>