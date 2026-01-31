@extends('layouts.app')

@section('title', 'Yangi mijoz')
@section('page-title', 'Yangi mijoz')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <div class="card p-4 sm:p-5">
        <form action="{{ route('customers.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Name -->
                <label class="block">
                    <span>Ismi / Tashkilot nomi <span class="text-error">*</span></span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text" name="name" value="{{ old('name') }}" required placeholder="Mijoz ismini kiriting" />
                    @error('name') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>

                <!-- Phone -->
                <label class="block">
                    <span>Telefon raqami <span class="text-error">*</span></span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text" name="phone" value="{{ old('phone') }}" required placeholder="+998901234567" />
                    @error('phone') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Telegram -->
                <label class="block">
                    <span>Telegram Username</span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text" name="telegram_username" value="{{ old('telegram_username') }}" placeholder="@username" />
                    @error('telegram_username') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>

                <!-- Address -->
                 <label class="block">
                    <span>Manzil</span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text" name="address" value="{{ old('address') }}" placeholder="Mijoz manzili" />
                    @error('address') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>
            </div>

             <!-- Description/Notes -->
             <label class="block">
                <span>Qo'shimcha ma'lumot (Izoh)</span>
                <textarea rows="3" name="notes" placeholder="Mijoz haqida izoh..." class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('notes') }}</textarea>
                @error('notes') <span class="text-tiny text-error">{{ $message }}</span> @enderror
            </label>

            <!-- Toggles -->
            <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-6">
                 <label class="inline-flex items-center space-x-2">
                    <input class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox" name="is_regular" value="1" {{ old('is_regular') ? 'checked' : '' }} />
                    <span>Doimiy xaridor</span>
                </label>

                <label class="inline-flex items-center space-x-2">
                    <input class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox" name="telegram_notifications" value="1" {{ old('telegram_notifications') ? 'checked' : '' }} />
                    <span>Telegram xabarnomalar yuborish</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <a href="{{ route('customers.index') }}" class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                    Bekor qilish
                </a>
                <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    Saqlash
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
