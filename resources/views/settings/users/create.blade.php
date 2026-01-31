@extends('layouts.app')

@section('title', 'Yangi Foydalanuvchi')
@section('page-title', 'Yangi Foydalanuvchi Yaratish')

@include('partials.sidebar-menu-users')

@section('content')
<div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
    <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
            Yangi Foydalanuvchi
        </h2>
    </div>
    <div class="flex justify-center space-x-2">
        <a href="{{ route('settings.users.index') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            Bekor qilish
        </a>
        <button form="user-form" type="submit" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            Saqlash
        </button>
    </div>
</div>

<form id="user-form" method="POST" action="{{ route('settings.users.store') }}">
    @csrf
    <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="card">
                <div class="tabs flex flex-col">
                    <div class="is-scrollbar-hidden overflow-x-auto">
                        <div class="border-b-2 border-slate-150 dark:border-navy-500">
                            <div class="tabs-list -mb-0.5 flex">
                                <button type="button" class="btn h-14 shrink-0 space-x-2 rounded-none border-b-2 border-primary px-4 font-medium text-primary dark:border-accent dark:text-accent-light sm:px-5">
                                    <i class="fa-solid fa-user text-base"></i>
                                    <span>Asosiy Ma'lumotlar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content p-4 sm:p-5">
                        <div class="space-y-5">
                            <label class="block">
                                <span class="font-medium text-slate-600 dark:text-navy-100">Ism Familiya</span>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror" placeholder="Masalan: Alisher Navoiy" required>
                                @error('name')
                                    <span class="text-tiny+ text-error">{{ $message }}</span>
                                @enderror
                            </label>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <label class="block">
                                    <span class="font-medium text-slate-600 dark:text-navy-100">Email</span>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('email') border-error @enderror" placeholder="example@email.com" required>
                                    @error('email')
                                        <span class="text-tiny+ text-error">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="block">
                                    <span class="font-medium text-slate-600 dark:text-navy-100">Telefon</span>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('phone') border-error @enderror" placeholder="+998 90 123 45 67">
                                    @error('phone')
                                        <span class="text-tiny+ text-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <label class="block">
                                    <span class="font-medium text-slate-600 dark:text-navy-100">Parol</span>
                                    <input type="password" name="password" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('password') border-error @enderror" placeholder="••••••••" required>
                                    @error('password')
                                        <span class="text-tiny+ text-error">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="block">
                                    <span class="font-medium text-slate-600 dark:text-navy-100">Parolni Tasdiqlash</span>
                                    <input type="password" name="password_confirmation" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="••••••••" required>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="card space-y-5 p-4 sm:p-5">
                <label class="block">
                    <span class="font-medium text-slate-600 dark:text-navy-100">Rol</span>
                    <select name="role" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent @error('role') border-error @enderror" required>
                        <option value="">Rolni tanlang</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-tiny+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="is_active" value="1" class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="font-medium text-slate-600 dark:text-navy-100">Faol foydalanuvchi</span>
                </label>

                <div class="rounded-lg bg-info/10 p-4 dark:bg-info/15">
                    <div class="flex items-start space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs+ font-medium text-slate-700 dark:text-navy-100">Eslatma</p>
                            <p class="mt-1 text-xs text-slate-600 dark:text-navy-200">
                                Foydalanuvchi yaratilgandan so'ng, tizimga kirish uchun email va paroldan foydalanadi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-warning/10 p-4 dark:bg-warning/15">
                    <div class="flex items-start space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="text-xs+ font-medium text-slate-700 dark:text-navy-100">Xavfsizlik</p>
                            <p class="mt-1 text-xs text-slate-600 dark:text-navy-200">
                                Parol kamida 8 ta belgidan iborat bo'lishi kerak. Kuchli parol tanlang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
