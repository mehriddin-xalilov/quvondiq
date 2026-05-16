@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-4 lg:gap-6">
    <div class="card px-5 py-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs+ uppercase text-slate-400 dark:text-navy-300">Foydalanuvchilar</p>
                <p class="mt-1 text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ $stats['total_users'] }}</p>
            </div>
            <div class="mask is-squircle flex size-10 shrink-0 items-center justify-center bg-info/10">
                <i class="fa-solid fa-users text-info text-xl"></i>
            </div>
        </div>
    </div>

    <div class="card px-5 py-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs+ uppercase text-slate-400 dark:text-navy-300">Shablonlar</p>
                <p class="mt-1 text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ $stats['total_templates'] }}</p>
            </div>
            <div class="mask is-squircle flex size-10 shrink-0 items-center justify-center bg-primary/10">
                <i class="fa-solid fa-file-word text-primary text-xl"></i>
            </div>
        </div>
    </div>

    <a href="{{ route('sertifikatlar.index') }}" class="card px-5 py-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs+ uppercase text-slate-400 dark:text-navy-300">Sertifikatlar</p>
                <p class="mt-1 text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ $stats['total_sertifikatlar'] }}</p>
            </div>
            <div class="mask is-squircle flex size-10 shrink-0 items-center justify-center bg-success/10">
                <i class="fa-solid fa-certificate text-success text-xl"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('guvohnomalar.index') }}" class="card px-5 py-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs+ uppercase text-slate-400 dark:text-navy-300">Guvohnomalar</p>
                <p class="mt-1 text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ $stats['total_guvohnomalar'] }}</p>
            </div>
            <div class="mask is-squircle flex size-10 shrink-0 items-center justify-center bg-warning/10">
                <i class="fa-solid fa-award text-warning text-xl"></i>
            </div>
        </div>
    </a>
</div>
@endsection