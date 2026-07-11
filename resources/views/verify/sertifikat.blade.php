<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sertifikat № {{ $sertifikat->raqam }} — Yem Do'koni CRM">
    <title>Sertifikat № {{ $sertifikat->raqam }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #c96442;
            --primary-dark: #a84a2a;
            --primary-light: #f6ece7;
            --primary-border: #eddbd1;
            --success: #057a55;
            --success-light: #def7ec;
            --text: #1f1e1c;
            --text-muted: #6f6c66;
            --border: #e8e4db;
            --bg: #f5f4ee;
            --card: #ffffff;
            --radius: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* HEADER */
        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px 16px;
            text-align: center;
        }
        .header-logo {
            font-size: 12px;
            font-weight: 500;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .header h1 {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 20px;
            font-weight: 600;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }

        /* BADGE */
        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--success-light);
            color: var(--success);
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 999px;
            margin: 16px auto 0;
        }
        .verified-badge svg { flex-shrink: 0; }

        /* CONTAINER */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 16px 40px;
        }

        /* CARD */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            overflow: hidden;
            margin-bottom: 16px;
        }
        .card-header {
            background: var(--primary-light);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--primary-border);
        }
        .card-header-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .card-header h2 {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
        }
        .card-body { padding: 0; }

        /* ROW */
        .info-row {
            display: flex;
            align-items: flex-start;
            padding: 13px 20px;
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            min-width: 140px;
            flex-shrink: 0;
            padding-top: 1px;
        }
        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            line-height: 1.4;
        }
        .info-value.highlight { color: var(--primary); }
        .badge-num {
            display: inline-block;
            background: var(--primary);
            color: white;
            font-size: 14px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 8px;
            letter-spacing: 0.5px;
        }

        /* QR SECTION */
        .qr-card {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            padding: 24px 20px;
            text-align: center;
            margin-bottom: 16px;
        }
        .qr-card svg, .qr-card img {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            border: 3px solid var(--primary-light);
        }
        @media (max-width: 768px) {
            .qr-card { display: none; }
        }
        .qr-hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 10px;
        }

        /* DOWNLOAD BTN */
        .btn-download {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-size: 15px;
            font-weight: 600;
            padding: 16px 24px;
            border-radius: var(--radius);
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(201,100,66,.35);
            transition: transform .15s, box-shadow .15s;
        }
        .btn-download:active {
            transform: scale(.97);
            box-shadow: 0 2px 6px rgba(201,100,66,.3);
        }


        /* FOOTER */
        .footer {
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            padding: 20px 16px;
        }

        @media (max-width: 400px) {
            .info-label { min-width: 100px; }
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-logo">Kasbiy Ko'nikmalar Markazi</div>
    <h1>Sertifikat № {{ $sertifikat->seria }}{{ $sertifikat->raqam }}</h1>
    <div class="verified-badge">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        Haqiqiyligi tasdiqlangan
    </div>
</div>

<div class="container">

    {{-- ASOSIY MA'LUMOT --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2>Xodim ma'lumotlari</h2>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="info-label">Sertifikat №</span>
                <span class="info-value"><span class="badge-num">{{ $sertifikat->seria }}{{ $sertifikat->raqam }}</span></span>
            </div>
            <div class="info-row">
                <span class="info-label">F.I.O.</span>
                <span class="info-value">{{ $sertifikat->fio_uz }}</span>
            </div>
        </div>
    </div>

    {{-- KASB --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2>Kasb va malaka</h2>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="info-label">Kasb (UZ)</span>
                <span class="info-value highlight">{{ $sertifikat->kasb_uz }}</span>
            </div>
            @if($sertifikat->kasb_en)
            <div class="info-row">
                <span class="info-label">Kasb (EN)</span>
                <span class="info-value">{{ $sertifikat->kasb_en }}</span>
            </div>
            @endif
            @if($sertifikat->kasb_ru)
            <div class="info-row">
                <span class="info-label">Kasb (RU)</span>
                <span class="info-value">{{ $sertifikat->kasb_ru }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- O'QISH MUDDATI --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <h2>O'qish muddati</h2>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="info-label">Boshlanish sanasi</span>
                <span class="info-value">{{ $sertifikat->boshlanish_sanasi?->format('d.m.Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tugash sanasi</span>
                <span class="info-value">{{ $sertifikat->tugash_sanasi?->format('d.m.Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Soat hajmi</span>
                <span class="info-value">{{ $sertifikat->soat }} soat</span>
            </div>
        </div>
    </div>

    {{-- DIREKTOR / RO'YXATGA OLISH --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h2>Tashkilot ma'lumotlari</h2>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="info-label">Direktor</span>
                <span class="info-value">{{ $sertifikat->direktor_fio }}</span>
            </div>
            @if($sertifikat->registratsiya_raqami)
            <div class="info-row">
                <span class="info-label">Ro'yxat raqami</span>
                <span class="info-value">{{ $sertifikat->registratsiya_raqami }}</span>
            </div>
            @endif
            @if($sertifikat->registratsiya_sanasi)
            <div class="info-row">
                <span class="info-label">Ro'yxat sanasi</span>
                <span class="info-value">{{ $sertifikat->registratsiya_sanasi->format('d.m.Y') }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- QR KOD --}}
    <div class="qr-card">
        {!! QrCode::size(150)->margin(1)->generate(route('sertifikat.verify', $sertifikat->verify_code)) !!}
        <p class="qr-hint">QR kodni skanerlash orqali haqiqiyligini tekshiring</p>
    </div>

    {{-- YUKLAB OLISH --}}
    @if($sertifikat->certificate_path)
        @php $ext = strtoupper(pathinfo($sertifikat->certificate_path, PATHINFO_EXTENSION) ?: 'PDF'); @endphp
        <a href="{{ route('sertifikat.download', $sertifikat->verify_code) }}" class="btn-download">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10z"/>
            </svg>
            Sertifikatni yuklab olish ({{ $ext }})
        </a>
    @endif

</div>

<div class="footer">
    © {{ date('Y') }} Kasbiy Ko'nikmalar Markazi<br>
    Sertifikat № {{ $sertifikat->raqam }} | Kod: {{ $sertifikat->verify_code }}
</div>

</body>
</html>
