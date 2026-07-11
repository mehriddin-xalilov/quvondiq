<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Гувоҳнома № {{ $guvohnoma->raqam }} — Назорат сифат таълим ўқув маркази">
    <title>Гувоҳнома № {{ $guvohnoma->raqam }} | Назорат сифат таълим</title>
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

        /* --- HEADER --- */
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

        /* --- BADGE --- */
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

        /* --- CONTAINER --- */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 16px 40px;
        }

        /* --- CARD --- */
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

        /* --- ROW --- */
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
            min-width: 130px;
            flex-shrink: 0;
            padding-top: 1px;
        }
        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            line-height: 1.4;
        }
        .info-value.highlight {
            color: var(--primary);
        }
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

        /* --- GRADES --- */
        .grades-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            padding: 16px;
        }
        .grade-cell {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            padding: 16px 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .grade-icon {
            width: 32px;
            height: 32px;
            background: var(--success);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .grade-label {
            font-size: 11px;
            color: #047857;
            font-weight: 500;
            line-height: 1.3;
        }
        .grade-value {
            font-size: 15px;
            font-weight: 700;
            color: #064e3b;
            line-height: 1.2;
        }
        @media (max-width: 400px) {
            .grades-grid { grid-template-columns: 1fr; }
        }

        /* --- QR SECTION --- */
        .qr-card {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            padding: 24px 20px;
            text-align: center;
            margin-bottom: 16px;
        }
        .qr-card img {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            border: 3px solid var(--primary-light);
        }
        /* Mobilda QR kerak emas — foydalanuvchi shu sahifaga QR orqali kelgan,
           va PDF ichida ham QR bor. */
        @media (max-width: 768px) {
            .qr-card { display: none; }
        }
        .qr-hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 10px;
        }

        /* --- DOWNLOAD BTN --- */
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
        /* Mobilda yuklab olish tugmasi yashiriladi — telefonga PDF kerak emas. */
        @media (max-width: 768px) {
            .btn-download { display: none; }
        }

        /* --- FOOTER --- */
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
    <div class="header-logo">Назорат сифат таълим ўқув маркази</div>
    <h1>Свидетельство № {{ $guvohnoma->raqam }}</h1>
    <div class="verified-badge">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        Подлинность подтверждена
    </div>
</div>

<div class="container">

    {{-- КАРТОЧКА: ОСНОВНАЯ ИНФОРМАЦИЯ --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2>Информация о владельце</h2>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="info-label">№ Свидетельства</span>
                <span class="info-value"><span class="badge-num">{{ $guvohnoma->raqam }}</span></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ф.И.О.</span>
                <span class="info-value">{{ $guvohnoma->fullNameRu() ?: $guvohnoma->fullNameOz() }}</span>
            </div>
        </div>
    </div>

    {{-- КАРТОЧКА: КВАЛИФИКАЦИЯ --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2>Присвоенная квалификация</h2>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="info-label">Специальность</span>
                <span class="info-value highlight">{{ $guvohnoma->mutaxassislik_ru ?? $guvohnoma->mutaxassislik_oz }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Разряд / Тойифа</span>
                <span class="info-value">{{ $guvohnoma->razryad }}-разряд</span>
            </div>
        </div>
    </div>

    {{-- КАРТОЧКА: СРОКИ --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <h2>Сроки обучения и выдачи</h2>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="info-label">Начало обучения</span>
                <span class="info-value">{{ $guvohnoma->boshlanish_sanasi?->format('d.m.Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Конец обучения</span>
                <span class="info-value">{{ $guvohnoma->tugash_sanasi?->format('d.m.Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Дата выдачи</span>
                <span class="info-value">{{ $guvohnoma->berilgan_sanasi?->format('d.m.Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">№ Протокола</span>
                <span class="info-value">{{ $guvohnoma->protokol_raqami }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Выдано в</span>
                <span class="info-value">{{ $guvohnoma->berilgan_joy_ru ?? $guvohnoma->berilgan_joy_oz }}</span>
            </div>
        </div>
    </div>

    {{-- ОЦЕНКИ --}}
    @if($guvohnoma->ball_umumiy_ru || $guvohnoma->ball_maxsus_ru)
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <h2>Результаты экзамена</h2>
        </div>
        <div class="card-body">
            <div class="grades-grid">
                <div class="grade-cell">
                    <span class="grade-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </span>
                    <div class="grade-label">Общий курс</div>
                    <div class="grade-value">{{ $guvohnoma->ball_umumiy_ru ?: $guvohnoma->ball_umumiy_oz ?: '—' }}</div>
                </div>
                <div class="grade-cell">
                    <span class="grade-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </span>
                    <div class="grade-label">Спец. курс</div>
                    <div class="grade-value">{{ $guvohnoma->ball_maxsus_ru ?: $guvohnoma->ball_maxsus_oz ?: '—' }}</div>
                </div>
                <div class="grade-cell">
                    <span class="grade-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                        </svg>
                    </span>
                    <div class="grade-label">Производство</div>
                    <div class="grade-value">{{ $guvohnoma->ball_ishlab_chiqarish_ru ?: $guvohnoma->ball_ishlab_chiqarish_oz ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- КОМИССИЯ --}}
    @if($guvohnoma->komissiya_raisi_fio || $guvohnoma->komissiya_azosi_fio || $guvohnoma->direktor_fio)
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2>Аттестационная комиссия</h2>
        </div>
        <div class="card-body">
            @if($guvohnoma->komissiya_raisi_fio)
            <div class="info-row">
                <span class="info-label">Председатель</span>
                <span class="info-value">{{ $guvohnoma->komissiya_raisi_fio }}</span>
            </div>
            @endif
            @if($guvohnoma->komissiya_azosi_fio)
            <div class="info-row">
                <span class="info-label">Член комиссии</span>
                <span class="info-value">{{ $guvohnoma->komissiya_azosi_fio }}</span>
            </div>
            @endif
            @if($guvohnoma->direktor_fio)
            <div class="info-row">
                <span class="info-label">Директор</span>
                <span class="info-value">{{ $guvohnoma->direktor_fio }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- QR КОД --}}
    <div class="qr-card">
        {!! QrCode::size(150)->margin(1)->generate(route('certificate.verify', $guvohnoma->verify_code)) !!}
        <p class="qr-hint">Отсканируйте QR-код для проверки подлинности</p>
    </div>

    {{-- СКАЧАТЬ --}}
    @if($guvohnoma->guvohnoma_path)
        @php $ext = strtoupper(pathinfo($guvohnoma->guvohnoma_path, PATHINFO_EXTENSION) ?: 'PDF'); @endphp
        <a href="{{ route('certificate.download', $guvohnoma->verify_code) }}" class="btn-download">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10z"/>
            </svg>
            Скачать свидетельство ({{ $ext }})
        </a>
    @endif

</div>

<div class="footer">
    © {{ date('Y') }} Назорат сифат таълим ўқув маркази<br>
    Гувоҳнома № {{ $guvohnoma->raqam }} | Код: {{ $guvohnoma->verify_code }}
</div>

</body>
</html>
