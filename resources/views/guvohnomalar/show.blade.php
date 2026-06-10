@extends('layouts.app')

@section('title', 'Guvohnoma № ' . $guvohnoma->raqam)
@section('page-title', 'Guvohnoma ma\'lumotlari')

@section('content')
<div class="flex items-center justify-between mt-4">
    <a href="{{ route('guvohnomalar.index') }}" class="text-primary text-sm hover:underline">← Ro'yxatga qaytish</a>
    <div class="space-x-2">
        @if($guvohnoma->guvohnoma_path)
        <a href="{{ route('guvohnomalar.download', $guvohnoma) }}" class="btn bg-success text-white hover:bg-success-focus">
            <i class="fa-solid fa-file-word mr-2"></i> Word Yuklab olish
        </a>
        @endif
        <a href="{{ route('guvohnomalar.edit', $guvohnoma) }}" class="btn bg-warning text-white">Tahrirlash</a>
    </div>
</div>

<div class="card p-5 mt-5">
    <h3 class="text-lg font-medium text-primary">
        Гувоҳнома № <span class="font-mono">{{ $guvohnoma->raqam }}</span>
    </h3>

    <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Ф.И.О.</p>
            <p>{{ trim(($guvohnoma->familiya_ru ?: $guvohnoma->familiya_oz) . ' ' . ($guvohnoma->ism_ru ?: $guvohnoma->ism_oz) . ' ' . ($guvohnoma->otasi_ismi_ru ?: $guvohnoma->otasi_ismi_oz)) }}</p>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Берилган жой</p>
            <p>{{ $guvohnoma->region?->name_uz ?? '—' }} → {{ $guvohnoma->district?->name_uz ?? '—' }}</p>
            <p>{{ $guvohnoma->berilgan_joy_oz }} / {{ $guvohnoma->berilgan_joy_ru }}</p>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Мутахассислик</p>
            <p>{{ $guvohnoma->mutaxassislik_oz }}</p>
            @if($guvohnoma->mutaxassislik_ru)<p class="text-slate-500">{{ $guvohnoma->mutaxassislik_ru }}</p>@endif
            <div class="flex items-center gap-2 mt-1 flex-wrap">
                @if($guvohnoma->razryad)
                    <span class="inline-flex items-center gap-1 text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium">
                        <i class="fa-solid fa-star text-[10px]"></i> Razryad: {{ $guvohnoma->razryad }}
                    </span>
                @endif
                @if($guvohnoma->speciality)
                    <span class="inline-flex items-center gap-1 text-xs bg-success/10 text-success px-2 py-0.5 rounded-full font-medium">
                        <i class="fa-solid fa-tag text-[10px]"></i> {{ $guvohnoma->speciality }}
                    </span>
                @endif
            </div>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Сана</p>
            <p>{{ $guvohnoma->boshlanish_sanasi?->format('d.m.Y') }} — {{ $guvohnoma->tugash_sanasi?->format('d.m.Y') }}</p>
            <p class="text-slate-500">Берилди: {{ $guvohnoma->berilgan_sanasi?->format('d.m.Y') }}</p>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Протокол</p>
            <p>№ {{ $guvohnoma->protokol_raqami }} — {{ $guvohnoma->protokol_sanasi?->format('d.m.Y') }}</p>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Масъул шахслар</p>
            <p><strong>Комиссия раиси:</strong> {{ $guvohnoma->komissiya_raisi_fio }}</p>
            @if($guvohnoma->komissiya_azosi_fio)<p><strong>Аъзоси:</strong> {{ $guvohnoma->komissiya_azosi_fio }}</p>@endif
            <p><strong>Директор:</strong> {{ $guvohnoma->direktor_fio }}</p>
        </div>

        <div class="lg:col-span-2">
            <p class="text-xs+ uppercase text-slate-400 mb-2">Баҳолар</p>
            <table class="w-full text-sm">
                <thead><tr class="text-slate-400 text-xs"><th class="text-left p-1">Курс</th><th class="text-left p-1">Кирилл</th><th class="text-left p-1">Русский</th></tr></thead>
                <tbody>
                    <tr><td class="p-1">Умумий</td><td class="p-1">{{ $guvohnoma->ball_umumiy_oz ?? '—' }}</td><td class="p-1">{{ $guvohnoma->ball_umumiy_ru ?? '—' }}</td></tr>
                    <tr><td class="p-1">Махсус</td><td class="p-1">{{ $guvohnoma->ball_maxsus_oz ?? '—' }}</td><td class="p-1">{{ $guvohnoma->ball_maxsus_ru ?? '—' }}</td></tr>
                    <tr><td class="p-1">Ишлаб чиқариш</td><td class="p-1">{{ $guvohnoma->ball_ishlab_chiqarish_oz ?? '—' }}</td><td class="p-1">{{ $guvohnoma->ball_ishlab_chiqarish_ru ?? '—' }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    @if(!$guvohnoma->guvohnoma_path)
    <div class="mt-5 p-3 rounded bg-error/10 text-error text-sm">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Hujjat hali generatsiya qilinmagan. Tahrirlab qayta saqlang.
    </div>
    @endif
</div>
@endsection