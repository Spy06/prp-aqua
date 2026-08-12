<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Administrator</title>
    <link rel="icon" type="image/png" href="{{ asset('images/aqua-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Glow Background */
        .bg-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.15) 0%, rgba(37, 99, 235, 0.08) 50%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
        }

        .it-card {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            width: 100%;
            max-width: 440px;
            padding: 36px 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
        }

        .it-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .it-logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #7c3aed, #2563eb);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(124, 58, 237, 0.35);
            margin-bottom: 14px;
        }
        .it-logo span { font-size: 28px; color: #ffffff; }

        .it-title {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .it-sub {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .it-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: rgba(124, 58, 237, 0.15);
            border: 1px solid rgba(124, 58, 237, 0.3);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            color: #c084fc;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 10px;
        }

        .it-field {
            margin-bottom: 18px;
        }

        .it-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .it-input-wrap {
            position: relative;
        }

        .it-input-wrap span {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 19px;
        }

        .it-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 12px 14px 12px 42px;
            color: #ffffff;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.2s ease;
            outline: none;
        }

        .it-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.25);
            background: rgba(15, 23, 42, 0.8);
        }

        .it-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #7c3aed, #2563eb);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.3);
            margin-top: 24px;
        }

        .it-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(124, 58, 237, 0.45);
        }

        .it-btn:active {
            transform: translateY(0);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <div class="bg-glow"></div>

    <div class="it-card">
        <div class="it-header">
            <div class="it-logo">
                <span class="material-symbols-outlined">admin_panel_settings</span>
            </div>
            <h1 class="it-title">Dashboard Super Administrator</h1>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <span class="material-symbols-outlined" style="font-size:18px;">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('it.login.submit') }}">
            @csrf

            {{-- Username --}}
            <div class="it-field">
                <label for="nik" class="it-label">Username</label>
                <div class="it-input-wrap">
                    <span class="material-symbols-outlined">badge</span>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" class="it-input" required autofocus />
                </div>
            </div>

            {{-- Password --}}
            <div class="it-field">
                <label for="password" class="it-label">Password</label>
                <div class="it-input-wrap">
                    <span class="material-symbols-outlined">key</span>
                    <input type="password" id="password" name="password" class="it-input" required />
                </div>
            </div>

            {{-- PIN Keamanan --}}
            <div class="it-field">
                <label for="secret_pin" class="it-label">PIN Keamanan</label>
                <div class="it-input-wrap">
                    <span class="material-symbols-outlined">security</span>
                    <input type="password" id="secret_pin" name="secret_pin" class="it-input" required />
                </div>
            </div>

            <button type="submit" class="it-btn">
                <span class="material-symbols-outlined" style="font-size:18px;">shield</span>
                Autentikasi & Masuk Portal
            </button>
        </form>
    </div>

</body>
</html>
