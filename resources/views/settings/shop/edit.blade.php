@extends('layouts.app')

@section('title', "Do'kon Sozlamalari")
@section('header-title', "Do'kon Ma'lumotlari")

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <div class="card p-4 sm:p-5">
        <header class="mb-4">
            <h2 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                Do'kon Ma'lumotlari
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-navy-300">
                Tizimda ko'rinadigan do'kon nomi va logotipini sozlang.
            </p>
        </header>

        @if(session('success'))
            <div class="alert flex rounded-lg border border-success/30 bg-success/10 py-4 px-4 text-success sm:px-5 mb-4">
                <div class="flex flex-1 items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form method="post" action="{{ route('settings.shop.update') }}" enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('put')

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">
                <!-- Logo Upload -->
                <div class="lg:col-span-1">
                    <label class="block mb-2 font-medium text-slate-700 dark:text-navy-100">Do'kon Logotipi</label>
                    <div class="flex flex-col items-center justify-center p-6 border border-dashed rounded-lg border-slate-300 dark:border-navy-450" 
                         x-data="{ 
                             logoPreview: '{{ $shop->logo_path ? asset('storage/' . $shop->logo_path) : asset('images/app-logo.png') }}',
                             updatePreview(event) {
                                 const file = event.target.files[0];
                                 if (file) {
                                     const reader = new FileReader();
                                     reader.onload = (e) => {
                                         this.logoPreview = e.target.result;
                                     };
                                     reader.readAsDataURL(file);
                                 }
                             }
                         }">
                        
                        <div class="relative w-32 h-32 mb-4 overflow-hidden rounded-full ring-4 ring-slate-100 dark:ring-navy-600 bg-white dark:bg-navy-700 shadow-lg flex items-center justify-center">
                            <img :src="logoPreview" class="object-contain w-24 h-24" alt="Logo">
                        </div>
                        
                        <label class="btn relative bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90 cursor-pointer shadow-md transition-transform active:scale-95">
                            <input tabindex="-1" type="file" name="logo" class="pointer-events-none absolute inset-0 h-full w-full opacity-0"
                                   accept="image/*"
                                   @change="updatePreview($event)">
                            <span class="flex items-center space-x-2">
                                <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                                <span>Logotipni Yuklash</span>
                            </span>
                        </label>
                        
                        <p class="mt-3 text-xs text-center text-slate-400 dark:text-navy-300">
                            JPG, PNG. Max 2MB.
                        </p>
                        @error('logo')
                            <span class="text-tiny+ text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Input Fields -->
                <div class="lg:col-span-2 space-y-4">
                    <label class="block">
                        <span>Do'kon Nomi</span>
                        <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                               placeholder="Do'kon nomini kiriting" 
                               type="text" 
                               name="name" 
                               value="{{ old('name', $shop->name) }}" 
                               required />
                        @error('name')
                            <span class="text-tiny+ text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="block">
                        <span>Manzil</span>
                        <textarea class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                  placeholder="Do'kon manzili" 
                                  name="address" 
                                  rows="3">{{ old('address', $shop->address) }}</textarea>
                        @error('address')
                            <span class="text-tiny+ text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span>Telefon Raqam</span>
                            <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                   placeholder="+998 90 123 45 67" 
                                   type="text" 
                                   name="phone" 
                                   value="{{ old('phone', $shop->phone) }}" />
                            @error('phone')
                                <span class="text-tiny+ text-error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span>Email</span>
                            <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                   placeholder="info@yemdokoni.uz" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email', $shop->email) }}" />
                            @error('email')
                                <span class="text-tiny+ text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span>Telegram Bot Token</span>
                            <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                   placeholder="Bot Token" 
                                   type="password" 
                                   name="telegram_bot_token" 
                                   value="{{ old('telegram_bot_token', $shop->telegram_bot_token) }}" />
                            @error('telegram_bot_token')
                                <span class="text-tiny+ text-error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span>Telegram Chat ID</span>
                            <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                   placeholder="Chat ID" 
                                   type="text" 
                                   name="telegram_chat_id" 
                                   value="{{ old('telegram_chat_id', $shop->telegram_chat_id) }}" />
                            @error('telegram_chat_id')
                                <span class="text-tiny+ text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

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
@endsection
