@extends('layouts.app')

@section('title', 'Profil Sozlamalari')
@section('header-title', 'Profil')

@push('styles')
    <style>
        .avatar-preview-wrapper {
            transition: all 0.3s ease;
        }
        .avatar-preview-wrapper:hover .avatar-overlay {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
<div class="flex flex-col gap-4 sm:gap-5 lg:gap-6" x-data="{ activeTab: 'general' }">
    
    <!-- Tab Headers -->
    <div class="card p-2">
        <div class="flex space-x-2 overflow-x-auto">
            <button @click="activeTab = 'general'"
                :class="activeTab === 'general' ? 'bg-primary text-white dark:bg-accent' : 'hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90'"
                class="btn shrink-0 space-x-2 rounded-lg px-4 font-medium transition-all duration-200">
                <i class="fa-solid fa-user"></i>
                <span>Umumiy Ma'lumotlar</span>
            </button>
            <button @click="activeTab = 'security'"
                :class="activeTab === 'security' ? 'bg-primary text-white dark:bg-accent' : 'hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90'"
                class="btn shrink-0 space-x-2 rounded-lg px-4 font-medium transition-all duration-200">
                <i class="fa-solid fa-lock"></i>
                <span>Xavfsizlik</span>
            </button>
            <button @click="activeTab = 'danger'"
                :class="activeTab === 'danger' ? 'bg-error text-white' : 'hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90'"
                class="btn shrink-0 space-x-2 rounded-lg px-4 font-medium transition-all duration-200 text-error">
                <i class="fa-solid fa-trash-alt"></i>
                <span>Hisobni O'chirish</span>
            </button>
        </div>
    </div>

    <!-- General Tab -->
    <div x-show="activeTab === 'general'" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="card p-4 sm:p-5">
            <header class="mb-4">
                <h2 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                    Profil Ma'lumotlari
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-navy-300">
                    Hisobingiz profil ma'lumotlari va elektron pochta manzilingizni yangilang.
                </p>
            </header>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-4">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">
                    <!-- Avatar Upload -->
                    <!-- Avatar Upload -->
                    <div class="lg:col-span-1">
                        <label class="block mb-2 font-medium text-slate-700 dark:text-navy-100">Profil Rasmi</label>
                        
                        <div class="flex flex-col items-center justify-center p-6 border border-dashed rounded-lg border-slate-300 dark:border-navy-450" 
                             x-data="{ 
                                 avatarPreview: '{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/avatar/avatar-12.jpg') }}',
                                 updatePreview(event) {
                                     const file = event.target.files[0];
                                     if (file) {
                                         const reader = new FileReader();
                                         reader.onload = (e) => {
                                             this.avatarPreview = e.target.result;
                                         };
                                         reader.readAsDataURL(file);
                                     }
                                 }
                             }">
                            
                            <div class="relative w-32 h-32 mb-4 overflow-hidden rounded-full ring-4 ring-slate-100 dark:ring-navy-600 avatar-preview-wrapper shadow-lg">
                                <img :src="avatarPreview" class="object-cover w-full h-full bg-slate-100 dark:bg-navy-700" alt="Avatar">
                                <div class="avatar-overlay absolute inset-0 flex items-center justify-center transition-opacity bg-black/40 opacity-0 duration-300">
                                    <i class="fa-solid fa-camera text-2xl text-white"></i>
                                </div>
                            </div>
                            
                            <label class="btn relative bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90 cursor-pointer shadow-md transition-transform active:scale-95">
                                <input tabindex="-1" type="file" name="avatar" class="pointer-events-none absolute inset-0 h-full w-full opacity-0"
                                       accept="image/*"
                                       @change="updatePreview($event)">
                                <span class="flex items-center space-x-2">
                                    <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                    <span>Rasmni O'zgartirish</span>
                                </span>
                            </label>
                            
                            <p class="mt-3 text-xs text-center text-slate-400 dark:text-navy-300">
                                JPG, PNG yoki GIF.<br>Maksimal hajmi: 2MB.
                            </p>
                        </div>
                    </div>

                    <!-- Input Fields -->
                    <div class="lg:col-span-2 space-y-4">
                        <label class="block">
                            <span>Ism</span>
                            <div class="relative flex mt-1.5">
                                <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                       placeholder="Ismingizni kiriting" 
                                       type="text" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required autofocus autocomplete="name" />
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <i class="fa-regular fa-user"></i>
                                </span>
                            </div>
                            @error('name')
                                <span class="text-tiny+ text-error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span>Email</span>
                            <div class="relative flex mt-1.5">
                                <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                       placeholder="Email manzilingizni kiriting" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required autocomplete="username" />
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                            </div>
                            @error('email')
                                <span class="text-tiny+ text-error">{{ $message }}</span>
                            @enderror
                        </label>
                        
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                Saqlash
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Tab -->
    <div x-show="activeTab === 'security'" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <div class="card p-4 sm:p-5">
            <header class="mb-4">
                <h2 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                    Parol Yangilash
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-navy-300">
                    Xavfsizlik uchun uzun va takrorlanmas parollardan foydalaning.
                </p>
            </header>

            <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-4">
                @csrf
                @method('put')

                <label class="block">
                    <span>Joriy Parol</span>
                    <div class="relative flex mt-1.5">
                        <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                               placeholder="Joriy parolni kiriting" 
                               type="password" 
                               name="current_password" 
                               autocomplete="current-password" />
                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                            <i class="fa-solid fa-key"></i>
                        </span>
                    </div>
                    @error('current_password')
                        <span class="text-tiny+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block">
                    <span>Yangi Parol</span>
                    <div class="relative flex mt-1.5">
                        <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                               placeholder="Yangi parolni kiriting" 
                               type="password" 
                               name="password" 
                               autocomplete="new-password" />
                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="text-tiny+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block">
                    <span>Parolni Tasdiqlash</span>
                    <div class="relative flex mt-1.5">
                        <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                               placeholder="Yangi parolni qayta kiriting" 
                               type="password" 
                               name="password_confirmation" 
                               autocomplete="new-password" />
                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                            <i class="fa-solid fa-check-double"></i>
                        </span>
                    </div>
                    @error('password_confirmation')
                        <span class="text-tiny+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                        Parolni Yangilash
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Tab -->
    <div x-show="activeTab === 'danger'" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <div class="card p-4 sm:p-5 border border-error/30 shadow-soft">
            <header class="mb-4">
                <h2 class="text-lg font-medium text-error">
                    Hisobni O'chirish
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-navy-300">
                    Hisobingiz o'chirilganda, barcha ma'lumotlaringiz butunlay yo'q qilinadi.
                </p>
            </header>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-4">
                @csrf
                @method('delete')

                <div class="flex flex-col space-y-4">
                   <p class="text-sm">O'chirishni tasdiqlash uchun parolingizni kiriting.</p>
                   
                   <label class="block max-w-md">
                        <div class="relative flex mt-1.5">
                            <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                   placeholder="Parolingizni kiriting" 
                                   type="password" 
                                   name="password" />
                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                <i class="fa-solid fa-key"></i>
                            </span>
                        </div>
                        @error('password', 'userDeletion')
                            <span class="text-tiny+ text-error">{{ $message }}</span>
                        @enderror
                   </label>

                   <div class="flex justify-start">
                        <button type="submit" class="btn bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90">
                            Hisobni O'chirish
                        </button>
                   </div>
                </div>
            </form>
        </div>
    </div>

</div>


@endsection
