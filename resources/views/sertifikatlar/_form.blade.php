@csrf
@php $s = $sertifikat ?? null; @endphp

<style>
    /* Tom Select options wrapping & styling */
    .ts-dropdown .option {
        white-space: normal !important;
        word-break: break-word !important;
        line-height: 1.4 !important;
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }
    
    /* Active option styling */
    .ts-dropdown .active {
        background-color: rgba(79, 70, 229, 0.2) !important;
        color: #fff !important;
    }

    .dark .ts-dropdown .option {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>
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
                        <i class="fa-solid fa-briefcase text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">3-bo'lim</p>
                        <h3 class="text-base font-medium">Kasb va davomiyligi</h3>
                    </div>
                </li>
                <li class="step space-x-4 before:bg-primary dark:before:bg-accent">
                    <div class="step-header mask is-hexagon bg-primary text-white dark:bg-accent">
                        <i class="fa-solid fa-stamp text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">4-bo'lim</p>
                        <h3 class="text-base font-medium">Direktor & ro'yxat</h3>
                    </div>
                </li>
            </ol>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-8 space-y-5" x-data="sertifikatSamplePicker()">
        @unless($s)
        {{-- "Eski sertifikatdan namuna" tugmasi (faqat yaratishda) --}}
        <div class="flex justify-end">
            <button type="button" @click="open()"
                    class="btn space-x-2 border border-primary text-primary hover:bg-primary/10 dark:border-accent dark:text-accent-light">
                <i class="fa-solid fa-copy"></i>
                <span>Eski sertifikatdan namuna olish</span>
            </button>
        </div>

        {{-- Modal --}}
        <template x-teleport="#x-teleport-target">
            <div x-show="visible" x-cloak
                 x-effect="document.body.style.overflow = visible ? 'hidden' : ''"
                 class="fixed inset-0 z-[100] flex flex-col items-center justify-center overflow-hidden px-4 py-6 sm:px-5"
                 role="dialog" @keydown.window.escape="close()">

                {{-- Overlay / Backdrop --}}
                <div class="absolute inset-0 bg-slate-900/60 transition-opacity duration-300"
                     @click="close()"
                     x-show="visible"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                </div>

                {{-- Modal Card --}}
                <div class="relative flex w-full max-w-xl max-h-[85vh] flex-col overflow-hidden rounded-lg bg-white dark:bg-navy-700 shadow-soft dark:shadow-soft-dark"
                     x-show="visible"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">

                    {{-- Header --}}
                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 dark:border-navy-500 sm:px-5">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-clock-rotate-left text-slate-400 dark:text-navy-300"></i>
                            <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Eski sertifikatlar</h4>
                        </div>
                        <button type="button" @click="close()"
                                class="btn size-7 rounded-full p-0 text-slate-400 hover:bg-slate-300/20 hover:text-slate-800 focus:bg-slate-300/20 dark:text-navy-300 dark:hover:bg-navy-600 dark:hover:text-navy-100">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    {{-- Search --}}
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-navy-500">
                        <label class="relative flex">
                            <input x-model.debounce.300ms="query" @input="load(1)"
                                   placeholder="Raqam, FIO, kasb..."
                                   class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent py-2 pl-9 pr-3 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            <div class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 transition-colors duration-200" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3.316 13.781l.73-.171-.73.171zm0-5.457l.73.171-.73-.171zm15.473 0l.73-.171-.73.171zm0 5.457l.73.171-.73-.171zm-5.008 5.008l-.171-.73.171.73zm-5.457 0l-.171.73.171-.73zm0-15.473l-.171-.73.171.73zm5.457 0l.171-.73-.171.73zM20.47 21.53a.75.75 0 101.06-1.06l-1.06 1.06zM4.046 13.61a11.198 11.198 0 010-5.115l-1.46-.342a12.698 12.698 0 000 5.8l1.46-.343zm14.013-5.115a11.196 11.196 0 010 5.115l1.46.342a12.698 12.698 0 000-5.8l-1.46.343zm-4.45 9.564a11.196 11.196 0 01-5.114 0l-.342 1.46c1.907.448 3.892.448 5.8 0l-.343-1.46zM8.496 4.046a11.198 11.198 0 015.115 0l.342-1.46a12.698 12.698 0 00-5.8 0l.343 1.46zm0 14.013a5.97 5.97 0 01-4.45-4.45l-1.46.343a7.47 7.47 0 005.568 5.568l.342-1.46zm5.457 1.46a7.47 7.47 0 005.568-5.567l-1.46-.342a5.97 5.97 0 01-4.45 4.45l.342 1.46zM13.61 4.046a5.97 5.97 0 014.45 4.45l1.46-.343a7.47 7.47 0 00-5.568-5.567l-.342 1.46zm-5.457-1.46a7.47 7.47 0 00-5.567 5.567l1.46.342a5.97 5.97 0 014.45-4.45l-.343-1.46zm8.652 15.28l3.665 3.664 1.06-1.06-3.665-3.665-1.06 1.06z"></path>
                                </svg>
                            </div>
                        </label>
                    </div>

                    {{-- Body --}}
                    <div class="flex-1 overflow-y-auto">
                        <template x-if="loading">
                            <div class="flex items-center justify-center p-10 text-slate-400 dark:text-navy-300">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                <span class="text-sm">Yuklanmoqda...</span>
                            </div>
                        </template>
                        <template x-if="!loading && items.length === 0">
                            <div class="flex flex-col items-center justify-center p-10 text-slate-400 dark:text-navy-300">
                                <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                                <span class="text-sm">Hech narsa topilmadi</span>
                            </div>
                        </template>
                        <table x-show="!loading && items.length > 0" class="is-hoverable w-full text-left text-sm">
                            <thead>
                                <tr>
                                    <th class="bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-600 dark:bg-navy-800 dark:text-navy-300 text-xs">Raqam</th>
                                    <th class="bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-600 dark:bg-navy-800 dark:text-navy-300 text-xs">F.I.O.</th>
                                    <th class="bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-600 dark:bg-navy-800 dark:text-navy-300 text-xs">Kasb</th>
                                    <th class="bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-600 dark:bg-navy-800 dark:text-navy-300 text-xs">Sana</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in items" :key="row.id">
                                    <tr @click="pick(row.id)"
                                        class="cursor-pointer border-y border-transparent border-b-slate-200 dark:border-b-navy-500 hover:bg-slate-100 dark:hover:bg-navy-600 transition-colors">
                                        <td class="px-4 py-2.5 font-mono text-xs text-slate-600 dark:text-navy-200" x-text="row.raqam"></td>
                                        <td class="px-4 py-2.5 font-medium text-slate-700 dark:text-navy-100" x-text="row.fio"></td>
                                        <td class="px-4 py-2.5 text-slate-500 dark:text-navy-300 text-xs" x-text="row.mutaxassislik"></td>
                                        <td class="px-4 py-2.5 text-slate-500 dark:text-navy-300 text-xs" x-text="row.sana"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Footer --}}
                    <div x-show="!loading && meta.last_page > 1"
                         class="flex items-center justify-between border-t border-slate-200 px-4 py-2.5 dark:border-navy-500">
                        <button type="button" @click="load(meta.current_page - 1)" :disabled="meta.current_page <= 1"
                                class="btn space-x-1.5 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-150 disabled:opacity-40 dark:border-navy-450 dark:text-navy-200 dark:hover:bg-navy-500">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            <span>Oldingi</span>
                        </button>
                        <span class="text-xs text-slate-400 dark:text-navy-300">
                            <span x-text="meta.current_page"></span> / <span x-text="meta.last_page"></span>
                            &nbsp;·&nbsp; jami <span x-text="meta.total"></span>
                        </span>
                        <button type="button" @click="load(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page"
                                class="btn space-x-1.5 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-150 disabled:opacity-40 dark:border-navy-450 dark:text-navy-200 dark:hover:bg-navy-500">
                            <span>Keyingi</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>

                </div>
            </div>
        </template>
        @endunless

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
                            <option value="{{ $tpl->id }}" @selected(old('template_id', $s?->template_id) == $tpl->id)>{{ $tpl->name }}</option>
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
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Xodim F.I.O.</h4>
                </div>
            </div>
            <div class="p-4 sm:p-5">
                <label class="block">
                    <span class="font-medium text-slate-600 dark:text-navy-100">Xodim F.I.O. <span class="text-error">*</span></span>
                    <input type="text" required name="fio_uz"
                           value="{{ old('fio_uz', $s?->fio_uz) }}"
                           placeholder="Familiya Ism Otasining ismi"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2.5 font-medium hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    @error('fio_uz')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>
            </div>
        </div>


        {{-- 4. Kasb va davomiyligi --}}
        <div class="card" x-data="{
            kasb_uz: '{{ old('kasb_uz', $s?->kasb_uz) }}',
            kasb_en: '{{ old('kasb_en', $s?->kasb_en) }}',
            kasb_ru: '{{ old('kasb_ru', $s?->kasb_ru) }}',

            updateKasb(el) {
                const opt = el.options[el.selectedIndex];
                if (opt && opt.value) {
                    this.kasb_uz = opt.getAttribute('data-name-uz') || '';
                    this.kasb_en = opt.getAttribute('data-name-en') || '';
                    this.kasb_ru = opt.getAttribute('data-name-ru') || '';
                } else {
                    this.kasb_uz = '';
                    this.kasb_en = '';
                    this.kasb_ru = '';
                }
            }
        }">
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
                    <span>Kasb <span class="text-error">*</span></span>
                    <select name="profession_id" required class="mt-1.5 w-full"
                            x-init="$el._x_tom = new Tom($el, { placeholder: '— Kasbni tanlang —' }); setTimeout(() => updateKasb($el), 100)"
                            @change="updateKasb($el)">
                        <option value=""></option>
                        @foreach($professions as $p)
                            <option value="{{ $p->id }}"
                                    data-name-uz="{{ $p->name_uz }}"
                                    data-name-en="{{ $p->name_en }}"
                                    data-name-ru="{{ $p->name_ru }}"
                                    @selected(old('profession_id', $s?->profession_id) == $p->id)>
                                {{ $p->name_uz }}
                            </option>
                        @endforeach
                    </select>
                    @error('profession_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>

                {{-- Hidden Inputs for kasb translations --}}
                <input type="hidden" name="kasb_uz" :value="kasb_uz">
                <input type="hidden" name="kasb_en" :value="kasb_en">
                <input type="hidden" name="kasb_ru" :value="kasb_ru">

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
                <i class="fa-solid fa-file-pdf mr-2"></i>
                {{ $s ? 'Saqlash va qayta generatsiya' : 'Saqlash va pdf yaratish' }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // "Eski sertifikatdan namuna olish" — modal + form to'ldirish
    window.sertifikatSamplePicker = function () {
        return {
            visible: false, loading: false, query: '',
            items: [], meta: { current_page: 1, last_page: 1, total: 0 },

            open() { this.visible = true; if (this.items.length === 0) this.load(1); },
            close() { this.visible = false; },

            async load(page) {
                this.loading = true;
                try {
                    const url = new URL("{{ route('sertifikatlar.samples') }}", window.location.origin);
                    url.searchParams.set('page', page || 1);
                    if (this.query) url.searchParams.set('q', this.query);
                    const r = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    const j = await r.json();
                    this.items = j.data || [];
                    this.meta = j.meta || this.meta;
                } catch (e) {
                    console.error(e);
                } finally {
                    this.loading = false;
                }
            },

            async pick(id) {
                this.loading = true;
                try {
                    const r = await fetch("{{ url('sertifikatlar') }}/" + id + "/sample-data", { headers: { 'Accept': 'application/json' } });
                    const data = await r.json();
                    this.fillForm(data);
                    this.close();
                    if (window.$notification) {
                        $notification({text: 'Namuna ma\'lumotlari to\'ldirildi', variant: 'success', position: 'right-top', duration: 3000});
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.loading = false;
                }
            },

            fillForm(data) {
                Object.entries(data).forEach(([name, value]) => {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (!el || value === null) return;

                    // Tom Select (profession, template)
                    if (el._x_tom) {
                        const tom = el.tomselect || el._x_tom;
                        if (tom) {
                            tom.setValue(String(value));
                            return;
                        }
                    }
                    // Flatpickr (sana)
                    if (el._x_flatpickr) {
                        el._x_flatpickr.setDate(value, true);
                        return;
                    }
                    el.value = value;
                });
            },
        };
    };
</script>
@endpush
