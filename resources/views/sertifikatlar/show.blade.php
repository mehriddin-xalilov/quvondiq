@extends('layouts.app')

@section('title', 'Sertifikat #' . $sertifikat->raqam)
@section('page-title', 'Sertifikat ma\'lumotlari')

@section('content')
@php
    $ext = $sertifikat->certificate_path
        ? strtolower(pathinfo($sertifikat->certificate_path, PATHINFO_EXTENSION))
        : null;
    $isPdf = $ext === 'pdf';
@endphp

<div class="flex items-center justify-between mt-4">
    <a href="{{ route('sertifikatlar.index') }}" class="text-primary text-sm hover:underline">← Ro'yxatga qaytish</a>
    <div class="space-x-2">
        @if($sertifikat->certificate_path)
        <a href="{{ route('sertifikatlar.download', $sertifikat) }}"
           class="btn {{ $isPdf ? 'bg-success' : 'bg-slate-500' }} text-white hover:opacity-90">
            <i class="fa-solid {{ $isPdf ? 'fa-file-pdf' : 'fa-file-word' }} mr-2"></i>
            {{ $isPdf ? 'PDF Yuklab olish' : '.docx Yuklab olish' }}
        </a>
        @endif
        <a href="{{ route('sertifikatlar.edit', $sertifikat) }}" class="btn bg-warning text-white">
            Tahrirlash
        </a>
    </div>
</div>

<div class="card p-5 mt-5">
    <h3 class="text-lg font-medium text-primary">
        Sertifikat № <span class="font-mono">{{ $sertifikat->seria }}{{ $sertifikat->raqam }}</span>
    </h3>

    <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">F.I.O.</p>
            <p>{{ $sertifikat->fio_uz }}</p>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Joy</p>
            <p>{{ $sertifikat->region?->name_uz }} — {{ $sertifikat->district?->name_uz ?? '—' }}</p>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Kasb</p>
            <p><strong>UZ:</strong> {{ $sertifikat->kasb_uz }}</p>
            @if($sertifikat->kasb_en)<p><strong>EN:</strong> {{ $sertifikat->kasb_en }}</p>@endif
            @if($sertifikat->kasb_ru)<p><strong>RU:</strong> {{ $sertifikat->kasb_ru }}</p>@endif
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Davomiyligi</p>
            <p>{{ $sertifikat->boshlanish_sanasi?->format('d.m.Y') }} — {{ $sertifikat->tugash_sanasi?->format('d.m.Y') }}</p>
            <p class="text-slate-500">{{ $sertifikat->soat }} soat</p>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Direktor</p>
            <p>{{ $sertifikat->direktor_fio }}</p>
        </div>

        <div>
            <p class="text-xs+ uppercase text-slate-400 mb-2">Ro'yxatga olish</p>
            <p>{{ $sertifikat->registratsiya_raqami ?? '—' }} — {{ $sertifikat->registratsiya_sanasi?->format('d.m.Y') ?? '—' }}</p>
        </div>
    </div>

    @if(!$sertifikat->certificate_path)
    <div class="mt-5 p-3 rounded bg-error/10 text-error text-sm">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Hujjat hali generatsiya qilinmagan. Tahrirlab qayta saqlang.
    </div>
    @elseif(!$isPdf)
    <div class="mt-5 p-3 rounded bg-warning/10 text-warning text-sm">
        <i class="fa-solid fa-circle-info"></i>
        Hujjat .docx formatida. PDF olish uchun — <a href="{{ route('sertifikatlar.edit', $sertifikat) }}" class="underline font-medium">Tahrirlab qayta saqlang</a>.
    </div>
    @endif
</div>
@endsection