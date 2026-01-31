@extends('layouts.app')

@section('title', 'Foydalanuvchini Tahrirlash')
@section('page-title', 'Foydalanuvchini Tahrirlash')

@include('partials.sidebar-menu-users')

@section('content')
<div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
    <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
            Tahrirlash: {{ $user->name }}
        </h2>
    </div>
    <div class="flex justify-center space-x-2">
        <a href="{{ route('settings.users.index') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            Bekor qilish
        </a>
        <button form="user-form" type="submit" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            Yangilash
        </button>
    </div>
</div>

<form id="user-form" method="POST" action="{{ route('settings.users.update', $user) }}">
    @csrf
    @method('PUT')
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
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror" placeholder="Masalan: Alisher Navoiy" required>
                                @error('name')
                                    <span class="text-tiny+ text-error">{{ $message }}</span>
                                @enderror
                            </label>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <label class="block">
                                    <span class="font-medium text-slate-600 dark:text-navy-100">Email</span>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('email') border-error @enderror" placeholder="example@email.com" required>
                                    @error('email')
                                        <span class="text-tiny+ text-error">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="block">
                                    <span class="font-medium text-slate-600 dark:text-navy-100">Telefon</span>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('phone') border-error @enderror" placeholder="+998 90 123 45 67">
                                    @error('phone')
                                        <span class="text-tiny+ text-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <label class="block">
                                    <span class="font-medium text-slate-600 dark:text-navy-100">Yangi Parol (ixtiyoriy)</span>
                                    <input type="password" name="password" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('password') border-error @enderror" placeholder="••••••••">
                                    @error('password')
                                        <span class="text-tiny+ text-error">{{ $message }}</span>
                                    @enderror
                                    <span class="text-xs text-slate-400 dark:text-navy-300">Bo'sh qoldiring agar parolni o'zgartirmoqchi bo'lmasangiz</span>
                                </label>

                                <label class="block">
                                    <span class="font-medium text-slate-600 dark:text-navy-100">Parolni Tasdiqlash</span>
                                    <input type="password" name="password_confirmation" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="••••••••">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="card space-y-5 p-4 sm:p-5">
                <div class="rounded-lg bg-slate-100 p-4 dark:bg-navy-600">
                    <div class="flex items-center space-x-3">
                        <div class="avatar size-12">
                            <img class="rounded-full" src="{{ $user->avatar ?? asset('images/avatar/avatar-12.jpg') }}" alt="avatar">
                        </div>
                        <div>
                            <p class="font-medium text-slate-700 dark:text-navy-100">{{ $user->name }}</p>
                            <p class="text-xs text-slate-400 dark:text-navy-300">ID: {{ $user->id }}</p>
                        </div>
                    </div>
                </div>

                <label class="block">
                    <span class="font-medium text-slate-600 dark:text-navy-100">Rol</span>
                    <select name="role" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent @error('role') border-error @enderror" required>
                        <option value="">Rolni tanlang</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ (old('role') ?? $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-tiny+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="is_active" value="1" class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <span class="font-medium text-slate-600 dark:text-navy-100">Faol foydalanuvchi</span>
                </label>

                <div class="rounded-lg bg-slate-100 p-4 dark:bg-navy-600">
                    <p class="text-xs+ text-slate-400 dark:text-navy-300">Yaratilgan</p>
                    <p class="text-sm font-medium text-slate-700 dark:text-navy-100">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                </div>

                @if($user->updated_at != $user->created_at)
                <div class="rounded-lg bg-slate-100 p-4 dark:bg-navy-600">
                    <p class="text-xs+ text-slate-400 dark:text-navy-300">Oxirgi yangilanish</p>
                    <p class="text-sm font-medium text-slate-700 dark:text-navy-100">{{ $user->updated_at->format('d.m.Y H:i') }}</p>
                </div>
                @endif

                <div class="rounded-lg bg-warning/10 p-4 dark:bg-warning/15">
                    <div class="flex items-start space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="text-xs+ font-medium text-slate-700 dark:text-navy-100">Diqqat</p>
                            <p class="mt-1 text-xs text-slate-600 dark:text-navy-200">
                                Rolni o'zgartirish foydalanuvchining tizimga kirishiga ta'sir qilishi mumkin.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
