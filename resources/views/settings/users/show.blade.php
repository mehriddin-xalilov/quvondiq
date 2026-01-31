@extends('layouts.app')

@section('title', 'Foydalanuvchi Ma\'lumotlari')
@section('page-title', 'Foydalanuvchi Ma\'lumotlari')

@include('partials.sidebar-menu-users')

@section('content')
<div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
    <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
            Foydalanuvchi: {{ $user->name }}
        </h2>
    </div>
    <div class="flex justify-center space-x-2">
        <a href="{{ route('settings.users.index') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            Orqaga
        </a>
        @can('edit-users')
        <a href="{{ route('settings.users.edit', $user) }}" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            Tahrirlash
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
    <div class="col-span-12 lg:col-span-4">
        <div class="card p-4 sm:p-5">
            <div class="flex flex-col items-center">
                <div class="avatar size-24">
                    <img class="rounded-full" src="{{ $user->avatar ?? asset('images/avatar/avatar-12.jpg') }}" alt="avatar">
                </div>
                <h3 class="mt-3 text-lg font-medium text-slate-700 dark:text-navy-100">
                    {{ $user->name }}
                </h3>
                <p class="text-xs+ text-slate-400 dark:text-navy-300">
                    {{ $user->roles->pluck('name')->implode(', ') }}
                </p>
                
                <div class="mt-4 flex w-full justify-between space-x-4">
                    <div class="text-center">
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">ID</p>
                        <p class="font-medium text-slate-700 dark:text-navy-100">#{{ $user->id }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Holat</p>
                        @if($user->is_active)
                        <span class="badge rounded-full bg-success/10 text-success dark:bg-success/15">
                            Faol
                        </span>
                        @else
                        <span class="badge rounded-full bg-error/10 text-error dark:bg-error/15">
                            Nofaol
                        </span>
                        @endif
                    </div>
                    <div class="text-center">
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Sotuvlar</p>
                        <p class="font-medium text-slate-700 dark:text-navy-100">{{ $user->sales->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4 p-4 sm:p-5">
            <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Bog'lanish</h4>
            <ul class="mt-4 space-y-4">
                <li class="flex items-center space-x-3">
                    <div class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Email</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-navy-100">{{ $user->email }}</p>
                    </div>
                </li>
                <li class="flex items-center space-x-3">
                    <div class="flex size-8 items-center justify-center rounded-lg bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Telefon</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-navy-100">{{ $user->phone ?? '-' }}</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="col-span-12 lg:col-span-8">
        <div class="card p-4 sm:p-5">
            <div class="flex items-center justify-between">
                <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Batafsil Ma'lumot</h4>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-lg border border-slate-150 p-3 dark:border-navy-500">
                    <p class="text-xs+ text-slate-400 dark:text-navy-300">Rollar</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($user->roles as $role)
                        <span class="badge rounded bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                            {{ $role->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-lg border border-slate-150 p-3 dark:border-navy-500">
                    <p class="text-xs+ text-slate-400 dark:text-navy-300">Yaratilgan</p>
                    <p class="mt-1 font-medium text-slate-700 dark:text-navy-100">
                        {{ $user->created_at->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="card mt-4 p-4 sm:p-5">
            <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Ruxsatlar</h4>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($user->getAllPermissions() as $permission)
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-slate-600 dark:text-navy-200">{{ $permission->name }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
