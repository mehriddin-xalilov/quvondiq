@csrf
@php $p = $profession ?? null; @endphp

<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
    <div class="col-span-12 lg:col-span-8">
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Mutaxassislik ma'lumotlari</h4>
                </div>
            </div>

            <div class="space-y-4 p-4 sm:p-5">
                <label class="block">
                    <span>Kod (ixtiyoriy, unique)</span>
                    <input name="code" value="{{ old('code', $p?->code) }}" placeholder="masalan: P-001"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    @error('code')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>

                <label class="block">
                    <span>Nomi — Lotin (O'zbek) <span class="text-error">*</span></span>
                    <input name="name_uz" required value="{{ old('name_uz', $p?->name_uz) }}"
                           placeholder="Elektrogazpayvandchi"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    @error('name_uz')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>

                <label class="block">
                    <span>Nomi — Kirill (Ўзбек)</span>
                    <input name="name_oz" value="{{ old('name_oz', $p?->name_oz) }}"
                           placeholder="Электрогазпайвандчи"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>

                <label class="block">
                    <span>Nomi — Русский</span>
                    <input name="name_ru" value="{{ old('name_ru', $p?->name_ru) }}"
                           placeholder="Электрогазосварщик"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>

                <label class="block">
                    <span>Nomi — English</span>
                    <input name="name_en" value="{{ old('name_en', $p?->name_en) }}"
                           placeholder="Electric gas welder"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>

            <div class="flex justify-end space-x-2 border-t border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <a href="{{ route('professions.index') }}"
                   class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500">
                    Bekor qilish
                </a>
                <button type="submit"
                        class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    <i class="fa-solid fa-save mr-2"></i>
                    {{ $p ? 'Saqlash' : 'Qo\'shish' }}
                </button>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4">
        <div class="card p-4 sm:p-5 sticky top-24">
            <div class="flex items-center space-x-2 mb-3">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-info/10 text-info">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Eslatma</h4>
            </div>
            <p class="text-xs+ text-slate-500 dark:text-navy-300">
                Mutaxassisliklar sertifikat va guvohnoma formalarida dropdown'da ko'rsatiladi.
            </p>
            <p class="text-xs+ text-slate-500 dark:text-navy-300 mt-2">
                Tilga oid nomlardan kerakli birini to'ldiring — qolganlarini keyinroq ham qo'shish mumkin.
            </p>
            <p class="text-xs+ text-slate-500 dark:text-navy-300 mt-3">
                <strong>Lotin (UZ)</strong> majburiy — formada asosiy ro'yxat shu maydonga ko'ra ko'rsatiladi.
            </p>
        </div>
    </div>
</div>
