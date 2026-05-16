@csrf
@php $s = $sertifikat ?? null; @endphp

<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
    <div class="col-span-12 lg:col-span-4 lg:place-items-start">
        <div class="sticky top-24">
            <ol class="steps is-vertical line-space [--size:2.75rem] [--line:.5rem]">
                <li class="step space-x-4 pb-8 before:bg-primary dark:before:bg-accent">
                    <div class="step-header mask is-hexagon bg-primary text-white dark:bg-accent">
                        <i class="fa-solid fa-id-card text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">1-bo'lim</p>
                        <h3 class="text-base font-medium text-primary dark:text-accent-light">Shablon & raqam</h3>
                    </div>
                </li>
                <li class="step space-x-4 pb-8 before:bg-primary dark:before:bg-accent">
                    <div class="step-header mask is-hexagon bg-primary text-white dark:bg-accent">
                        <i class="fa-solid fa-user text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">2-bo'lim</p>
                        <h3 class="text-base font-medium">Xodim F.I.O.</h3>
                    </div>
                </li>
                <li class="step space-x-4 pb-8 before:bg-primary dark:before:bg-accent">
                    <div class="step-header mask is-hexagon bg-primary text-white dark:bg-accent">
                        <i class="fa-solid fa-location-dot text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">3-bo'lim</p>
                        <h3 class="text-base font-medium">Joylashuv</h3>
                    </div>
                </li>
                <li class="step space-x-4 pb-8 before:bg-primary dark:before:bg-accent">
                    <div class="step-header mask is-hexagon bg-primary text-white dark:bg-accent">
                        <i class="fa-solid fa-briefcase text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">4-bo'lim</p>
                        <h3 class="text-base font-medium">Kasb va davomiyligi</h3>
                    </div>
                </li>
                <li class="step space-x-4 before:bg-primary dark:before:bg-accent">
                    <div class="step-header mask is-hexagon bg-primary text-white dark:bg-accent">
                        <i class="fa-solid fa-stamp text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">5-bo'lim</p>
                        <h3 class="text-base font-medium">Direktor & ro'yxat</h3>
                    </div>
                </li>
            </ol>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-8 space-y-5">
        {{-- 1. Shablon & raqam --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Shablon va raqam</h4>
                </div>
            </div>
            <div class="space-y-4 p-4 sm:p-5">
                <label class="block">
                    <span>Shablon <span class="text-error">*</span></span>
                    <select name="template_id" required class="mt-1.5 w-full"
                            x-init="$el._x_tom = new Tom($el, { placeholder: '— Shablonni tanlang —' })">
                        <option value=""></option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" @selected(old('template_id') == $tpl->id)>{{ $tpl->name }}</option>
                        @endforeach
                    </select>
                    @error('template_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span>Seriya</span>
                        <input name="seria" value="{{ old('seria', $s?->seria) }}" placeholder="QV"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>

                    <label class="block">
                        <span>Sertifikat raqami <span class="text-error">*</span></span>
                        <input name="raqam" required value="{{ old('raqam', $s?->raqam) }}" placeholder="012880"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        @error('raqam')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </label>
                </div>
            </div>
        </div>

        {{-- 2. Xodim F.I.O. --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Xodim ma'lumotlari</h4>
                </div>
            </div>
            <div class="space-y-5 p-4 sm:p-5">
                @foreach([['uz', 'Lotin (O\'zbek)'], ['en', 'Inglizcha'], ['ru', 'Ruscha']] as [$lang, $label])
                <div>
                    <p class="text-xs+ uppercase tracking-wide text-slate-400 dark:text-navy-300 mb-2">{{ $label }}</p>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <label class="block">
                            <span>Familiya {!! $lang === 'uz' ? '<span class="text-error">*</span>' : '' !!}</span>
                            <input name="familiya_{{ $lang }}" {{ $lang === 'uz' ? 'required' : '' }}
                                   value="{{ old('familiya_'.$lang, $s?->{'familiya_'.$lang}) }}"
                                   class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        </label>
                        <label class="block">
                            <span>Ism {!! $lang === 'uz' ? '<span class="text-error">*</span>' : '' !!}</span>
                            <input name="ism_{{ $lang }}" {{ $lang === 'uz' ? 'required' : '' }}
                                   value="{{ old('ism_'.$lang, $s?->{'ism_'.$lang}) }}"
                                   class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        </label>
                        <label class="block">
                            <span>Otasining ismi</span>
                            <input name="otasi_ismi_{{ $lang }}"
                                   value="{{ old('otasi_ismi_'.$lang, $s?->{'otasi_ismi_'.$lang}) }}"
                                   class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 3. Joylashuv --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Joylashuv</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-5">
                <label class="block">
                    <span>Viloyat</span>
                    <select name="region_id" class="region-select mt-1.5 w-full">
                        <option value=""></option>
                        @foreach($regions as $r)
                            <option value="{{ $r->id }}" @selected(old('region_id', $s?->region_id) == $r->id)>{{ $r->name_uz }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span>Tuman</span>
                    <select name="district_id" class="district-select mt-1.5 w-full">
                        <option value=""></option>
                        @foreach($districts as $d)
                            <option value="{{ $d->id }}" data-region="{{ $d->region_id }}"
                                    @selected(old('district_id', $s?->district_id) == $d->id)>{{ $d->name_uz }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>

        {{-- 4. Kasb va davomiyligi --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Kasb va davomiyligi</h4>
                </div>
            </div>
            <div class="space-y-4 p-4 sm:p-5">
                <label class="block">
                    <span>Kasb ma'lumotnomadan (ixtiyoriy)</span>
                    <select name="profession_id" class="mt-1.5 w-full"
                            x-init="$el._x_tom = new Tom($el, { placeholder: '— Tanlang yoki qo\'lda kiriting —' })">
                        <option value=""></option>
                        @foreach($professions as $p)
                            <option value="{{ $p->id }}" @selected(old('profession_id', $s?->profession_id) == $p->id)>{{ $p->name_uz }}</option>
                        @endforeach
                    </select>
                </label>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <label class="block">
                        <span>Kasb (Lotin) <span class="text-error">*</span></span>
                        <input name="kasb_uz" required value="{{ old('kasb_uz', $s?->kasb_uz) }}"
                               placeholder="Elektrogazpayvandchi"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>
                    <label class="block">
                        <span>Kasb (English)</span>
                        <input name="kasb_en" value="{{ old('kasb_en', $s?->kasb_en) }}" placeholder="Electric gas welder"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>
                    <label class="block">
                        <span>Kasb (Русский)</span>
                        <input name="kasb_ru" value="{{ old('kasb_ru', $s?->kasb_ru) }}" placeholder="Электрогазосварщик"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <label class="block">
                        <span>Boshlanish sanasi <span class="text-error">*</span></span>
                        <span class="relative mt-1.5 flex">
                            <input name="boshlanish_sanasi" type="text" required
                                   value="{{ old('boshlanish_sanasi', $s?->boshlanish_sanasi?->format('Y-m-d')) }}"
                                   x-init="$el._x_flatpickr = flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd.m.Y', allowInput: true })"
                                   placeholder="Sanani tanlang"
                                   class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                <i class="fa-regular fa-calendar"></i>
                            </span>
                        </span>
                    </label>
                    <label class="block">
                        <span>Tugash sanasi <span class="text-error">*</span></span>
                        <span class="relative mt-1.5 flex">
                            <input name="tugash_sanasi" type="text" required
                                   value="{{ old('tugash_sanasi', $s?->tugash_sanasi?->format('Y-m-d')) }}"
                                   x-init="$el._x_flatpickr = flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd.m.Y', allowInput: true })"
                                   placeholder="Sanani tanlang"
                                   class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                <i class="fa-regular fa-calendar"></i>
                            </span>
                        </span>
                    </label>
                    <label class="block">
                        <span>Soat <span class="text-error">*</span></span>
                        <input name="soat" type="number" required min="1" value="{{ old('soat', $s?->soat) }}" placeholder="360"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>
                </div>
            </div>
        </div>

        {{-- 5. Direktor & ro'yxat --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid fa-stamp"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Direktor va ro'yxatga olish</h4>
                </div>
            </div>
            <div class="space-y-4 p-4 sm:p-5">
                <label class="block">
                    <span>Direktor F.I.Sh. <span class="text-error">*</span></span>
                    <input name="direktor_fio" required value="{{ old('direktor_fio', $s?->direktor_fio) }}"
                           placeholder="XONALIYEV UMIDJON BARNOYEVICH"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span>Ro'yxatga olish raqami</span>
                        <input name="registratsiya_raqami" value="{{ old('registratsiya_raqami', $s?->registratsiya_raqami) }}" placeholder="6550"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>
                    <label class="block">
                        <span>Ro'yxatga olish sanasi</span>
                        <span class="relative mt-1.5 flex">
                            <input name="registratsiya_sanasi" type="text"
                                   value="{{ old('registratsiya_sanasi', $s?->registratsiya_sanasi?->format('Y-m-d')) }}"
                                   x-init="$el._x_flatpickr = flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd.m.Y', allowInput: true })"
                                   placeholder="Sanani tanlang"
                                   class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                <i class="fa-regular fa-calendar"></i>
                            </span>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('sertifikatlar.index') }}"
               class="btn min-w-[7rem] border border-slate-300 text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500">
                Bekor qilish
            </a>
            <button type="submit"
                    class="btn min-w-[8rem] bg-primary text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                <i class="fa-solid fa-file-circle-check mr-2"></i>
                {{ $s ? 'Saqlash va qayta generatsiya' : 'Saqlash va docx yaratish' }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const regionEl = document.querySelector('.region-select');
        const districtEl = document.querySelector('.district-select');

        const allDistricts = Array.from(districtEl.options).map(o => ({
            value: o.value, text: o.text, region: o.dataset.region, selected: o.selected
        })).filter(o => o.value);

        const tomDistrict = new Tom(districtEl, { placeholder: '— Tuman —', allowEmptyOption: true });
        const tomRegion = new Tom(regionEl, {
            placeholder: '— Viloyat —',
            allowEmptyOption: true,
            onChange(value) { filterDistricts(value); }
        });

        function filterDistricts(regionId) {
            const current = tomDistrict.getValue();
            tomDistrict.clear();
            tomDistrict.clearOptions();
            tomDistrict.addOption({ value: '', text: '— Tuman —' });
            allDistricts
                .filter(d => !regionId || d.region === String(regionId))
                .forEach(d => tomDistrict.addOption({ value: d.value, text: d.text }));
            tomDistrict.refreshOptions(false);
            if (current && allDistricts.find(d => d.value === current && (!regionId || d.region === String(regionId)))) {
                tomDistrict.setValue(current);
            }
        }

        // Initial filter
        filterDistricts(tomRegion.getValue());
        const preSelected = allDistricts.find(d => d.selected);
        if (preSelected) tomDistrict.setValue(preSelected.value);
    });
</script>
@endpush