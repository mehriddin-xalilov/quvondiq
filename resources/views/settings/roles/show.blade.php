@extends('layouts.app')

@section('title', 'Rol Ma\'lumotlari')
@section('page-title', 'Rol Ma\'lumotlari')

@include('partials.sidebar-menu-users')

@section('content')
<div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
    <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
            Rol: {{ $role->name }}
        </h2>
    </div>
    <div class="flex justify-center space-x-2">
        <a href="{{ route('settings.roles.index') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            Orqaga
        </a>
        @can('roles.edit')
        <a href="{{ route('settings.roles.edit', $role) }}" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            Tahrirlash
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
    <div class="col-span-12 lg:col-span-4">
        <div class="card p-4 sm:p-5">
            <div class="flex flex-col items-center">
                <div class="flex size-20 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="mt-3 text-lg font-medium text-slate-700 dark:text-navy-100">
                    {{ $role->name }}
                </h3>
                <p class="text-xs+ text-slate-400 dark:text-navy-300">
                    Guard: {{ $role->guard_name }}
                </p>
                
                <div class="mt-4 flex w-full justify-between space-x-4">
                    <div class="text-center">
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">ID</p>
                        <p class="font-medium text-slate-700 dark:text-navy-100">#{{ $role->id }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Foydalanuvchilar</p>
                        <p class="font-medium text-slate-700 dark:text-navy-100">{{ $role->users->count() }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Ruxsatlar</p>
                        <p class="font-medium text-slate-700 dark:text-navy-100">{{ $role->permissions->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 p-4 sm:p-5">
            <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Foydalanuvchilar</h4>
            <div class="mt-4 space-y-3">
                @forelse($role->users->take(5) as $user)
                <div class="flex items-center space-x-3">
                    <div class="avatar size-8">
                        <img class="rounded-full" src="{{ $user->avatar ?? asset('images/avatar/avatar-12.jpg') }}" alt="avatar">
                    </div>
                    <div>
                        <p class="font-medium text-slate-700 dark:text-navy-100">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400 dark:text-navy-300">ID: {{ $user->id }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-xs+ text-slate-400 dark:text-navy-300">Bu rolga foydalanuvchilar biriktirilmagan</p>
                @endforelse
                
                @if($role->users->count() > 5)
                <div class="mt-2 text-center">
                    <a href="{{ route('settings.users.index') }}" class="text-xs font-medium text-primary hover:underline dark:text-accent-light">
                        Barchasini ko'rish (+{{ $role->users->count() - 5 }})
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-8">
        <div class="card p-4 sm:p-5">
            <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Ruxsatlar Ro'yxati</h4>
            @php
                $permissions = $role->permissions->groupBy(function($item) {
                    return explode('-', $item->name)[1] ?? 'boshqa';
                });
            @endphp

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach($permissions as $group => $items)
                <div class="rounded-lg border border-slate-150 p-3 dark:border-navy-500">
                    <h5 class="mb-2 font-medium capitalize text-slate-700 dark:text-navy-100">{{ $group }}</h5>
                    <div class="flex flex-wrap gap-2">
                        @foreach($items as $permission)
                        <span class="badge rounded bg-success/10 text-success dark:bg-success/15">
                            {{ $permission->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
