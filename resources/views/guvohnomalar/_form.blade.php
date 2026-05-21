@csrf
@php $g = $guvohnoma ?? null; @endphp

<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
    <div class="col-span-12 lg:col-span-4 lg:place-items-start">
        <div class="sticky top-24">
            <ol class="steps is-vertical line-space [--size:2.75rem] [--line:.5rem]">
                @foreach([
                    ['fa-id-card', '1-bo\'lim', 'Shablon & raqam'],
                    ['fa-user', '2-bo\'lim', 'Xodim F.I.O.'],
                    ['fa-location-dot', '3-bo\'lim', 'Joylashuvi'],
                    ['fa-briefcase', '4-bo\'lim', 'Mutaxassislik'],
                    ['fa-calendar', '5-bo\'lim', 'Sana & protokol'],
                    ['fa-users', '6-bo\'lim', 'Mas\'ul shaxslar'],
                    ['fa-star', '7-bo\'lim', 'Baholar'],
                ] as $i => [$icon, $step, $title])
                <li class="step space-x-4 {{ $loop->last ? '' : 'pb-8' }} before:bg-primary dark:before:bg-accent">
                    <div class="step-header mask is-hexagon bg-primary text-white dark:bg-accent">
                        <i class="fa-solid {{ $icon }} text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">{{ $step }}</p>
                        <h3 class="text-base font-medium {{ $loop->first ? 'text-primary dark:text-accent-light' : '' }}">{{ $title }}</h3>
                    </div>
                </li>
                @endforeach
            </ol>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-8 space-y-5" x-data="guvohnomaSamplePicker()">
        @unless($g)
        {{-- "Eski guvohnomadan namuna" tugmasi (faqat yaratishda) --}}
        <div class="flex justify-end">
            <button type="button" @click="open()"
                    class="btn space-x-2 border border-primary text-primary hover:bg-primary/10 dark:border-accent dark:text-accent-light">
                <i class="fa-solid fa-copy"></i>
                <span>Eski guvohnomadan namuna olish</span>
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
                            <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Eski guvohnomalar</h4>
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
                                   placeholder="Raqam, F.I.O., mutaxassislik..."
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
                                    <th class="bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-600 dark:bg-navy-800 dark:text-navy-300 text-xs">Mutaxassislik</th>
                                    <th class="bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-600 dark:bg-navy-800 dark:text-navy-300 text-xs">Razryad</th>
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
                                        <td class="px-4 py-2.5 text-slate-600 dark:text-navy-200" x-text="row.razryad"></td>
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

        {{-- 1. Шаблон & рақам --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Shablon va raqam</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-5">
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

                <label class="block">
                    <span>Guvohnoma raqami <span class="text-error">*</span></span>
                    <input name="raqam" required value="{{ old('raqam', $g?->raqam) }}" placeholder="01489"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    @error('raqam')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>
            </div>
        </div>

        {{-- 2. Ходим Ф.И.О. --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Xodim F.I.O.</h4>
                </div>
            </div>
            <div class="p-4 sm:p-5">
                <div class="flex flex-col gap-5 sm:flex-row">
                    {{-- 3x4 rasm yuklash --}}
                    <div class="flex flex-col items-center justify-center border-r border-slate-200 dark:border-navy-500 pr-5 pb-5 sm:pb-0" style="width: 160px; flex-shrink: 0;">
                        <span class="text-slate-600 dark:text-navy-100 font-medium text-xs mb-3 text-center">Xodim rasmi (3x4)</span>
                        <div x-data="{
                            previewUrl: '{{ $g?->photo_path ? Storage::disk('public')->url($g->photo_path) : '' }}',
                            triggerFileSelect() { this.$refs.fileInput.click() },
                            handleFile(e) {
                                const file = e.target.files[0];
                                if (!file) return;
                                if (file.size > 2048 * 1024) {
                                    alert('Rasm o\'lchami 2MB dan oshmasligi kerak!');
                                    this.$refs.fileInput.value = '';
                                    return;
                                }
                                const reader = new FileReader();
                                reader.onload = (event) => { this.previewUrl = event.target.result; };
                                reader.readAsDataURL(file);
                            }
                        }" class="flex flex-col items-center">
                            
                            {{-- Clickable Preview Box --}}
                            <div @click="triggerFileSelect()" 
                                 class="relative flex cursor-pointer items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 transition-all hover:border-primary hover:bg-slate-100 dark:border-navy-450 dark:bg-navy-800 dark:hover:border-accent"
                                 style="width: 120px; height: 160px;">
                                
                                <template x-if="previewUrl">
                                    <img :src="previewUrl" class="h-full w-full object-contain" style="width: 100%; height: 100%; object-fit: contain;">
                                </template>
                                <template x-if="!previewUrl">
                                    <div class="flex flex-col items-center text-center p-2 text-slate-400 dark:text-navy-300">
                                        <i class="fa-solid fa-camera text-2xl mb-1.5 text-slate-400"></i>
                                        <span class="text-[10px] font-semibold leading-tight">Rasm yuklash<br>(3x4, max 2MB)</span>
                                    </div>
                                </template>
                                
                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity hover:opacity-100">
                                    <i class="fa-solid fa-pencil text-white text-lg"></i>
                                </div>
                            </div>
                            
                            <input type="file" name="photo" x-ref="fileInput" class="hidden" accept="image/*" @change="handleFile">
                            @error('photo')<span class="text-error text-xs mt-1 text-center">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    {{-- FIO Inputs --}}
                    <div class="flex-1 space-y-4">
                        <p class="text-xs+ uppercase tracking-wide text-slate-400 dark:text-navy-300">Kirill alifbosida kiriting</p>
                        <div class="space-y-4">
                            <label class="block">
                                <span>Familiya <span class="text-error">*</span></span>
                                <input name="familiya_ru" required
                                       value="{{ old('familiya_ru', $g?->familiya_ru ?? $g?->familiya_oz) }}"
                                       placeholder="Ivanov"
                                       class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            </label>
                            <label class="block">
                                <span>Ism <span class="text-error">*</span></span>
                                <input name="ism_ru" required
                                       value="{{ old('ism_ru', $g?->ism_ru ?? $g?->ism_oz) }}"
                                       placeholder="Ivan"
                                       class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            </label>
                            <label class="block">
                                <span>Otasining ismi</span>
                                <input name="otasi_ismi_ru"
                                       value="{{ old('otasi_ismi_ru', $g?->otasi_ismi_ru ?? $g?->otasi_ismi_oz) }}"
                                       placeholder="Petrovich"
                                       class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Жойлашуви --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Joylashuvi</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-5">
                <label class="block">
                    <span>Berilgan joy (Kirill)</span>
                    <input name="berilgan_joy_oz" value="{{ old('berilgan_joy_oz', $g?->berilgan_joy_oz) }}" placeholder="Qarshi"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
                <label class="block">
                    <span>Выдано (Русский)</span>
                    <input name="berilgan_joy_ru" value="{{ old('berilgan_joy_ru', $g?->berilgan_joy_ru) }}" placeholder="Карши"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>
        </div>

        {{-- 4. Мутахассислик --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Mutaxassislik</h4>
                </div>
            </div>
            <div class="space-y-4 p-4 sm:p-5">

                <div class="grid grid-cols-1 gap-4">
                    <label class="block">
                        <span>Mutaxassislik (Kirill) <span class="text-error">*</span></span>
                        <textarea name="mutaxassislik_oz" required rows="2"
                                  placeholder="Po'lat va temir-beton konstruksiyalarni montaj qilish bo'yicha montajchi"
                                  class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('mutaxassislik_oz', $g?->mutaxassislik_oz) }}</textarea>
                    </label>
                    {{-- Mutaxassislik (Ruscha) — to'liq qator --}}
                    <label class="block">
                        <span>Mutaxassislik (Ruscha)</span>
                        <textarea name="mutaxassislik_ru" rows="2"
                                  placeholder="Montajnik po montaju..."
                                  class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('mutaxassislik_ru', $g?->mutaxassislik_ru) }}</textarea>
                    </label>

                    {{-- Razryad va Speciality — yangi qator, 2 ustun --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span>Razryad</span>
                            <input name="razryad" value="{{ old('razryad', $g?->razryad) }}" placeholder="5"
                                   class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        </label>
                        <label class="block">
                            <span>Speciality</span>
                            <input name="speciality" value="{{ old('speciality', $g?->speciality) }}"
                                   placeholder="Mas: KM, EG"
                                   maxlength="32"
                                   class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            <p class="text-xs text-slate-400 mt-0.5">Soha qisqartmasi</p>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Сана & протокол --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary">
                        <i class="fa-solid fa-calendar"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Sana va protokol</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 lg:grid-cols-4 sm:p-5"
                 x-data="{
                     startDate: '{{ old('boshlanish_sanasi', $g?->boshlanish_sanasi?->format('Y-m-d')) }}',
                     davomiylik: '',
                     calcEnd() {
                         if (!this.startDate || !this.davomiylik) return;
                         const days = parseInt(this.davomiylik);
                         if (isNaN(days) || days < 1) return;
                         const d = new Date(this.startDate);
                         d.setDate(d.getDate() + days - 1);
                         const iso = d.getFullYear() + '-'
                             + String(d.getMonth()+1).padStart(2,'0') + '-'
                             + String(d.getDate()).padStart(2,'0');
                         const endEl = document.querySelector('[name=tugash_sanasi]');
                         if (endEl?._x_flatpickr) endEl._x_flatpickr.setDate(iso, true);
                     }
                 }">

                {{-- Boshlanish sanasi --}}
                <label class="block">
                    <span>Boshlanish sanasi <span class="text-error">*</span></span>
                    <span class="relative mt-1.5 flex">
                        <input name="boshlanish_sanasi" type="text" required
                               value="{{ old('boshlanish_sanasi', $g?->boshlanish_sanasi?->format('Y-m-d')) }}"
                               x-init="$el._x_flatpickr = flatpickr($el, {
                                   dateFormat: 'Y-m-d', altInput: true, altFormat: 'd.m.Y', allowInput: true,
                                   onChange: (sel, str) => { startDate = str; calcEnd(); }
                               })"
                               placeholder="Tanlang"
                               class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                            <i class="fa-regular fa-calendar"></i>
                        </span>
                    </span>
                </label>

                {{-- Davomiylik (kun) --}}
                <label class="block">
                    <span>Davomiylik <span class="text-slate-400 text-xs">(kun)</span></span>
                    <div class="relative mt-1.5 flex">
                        <input type="number" min="1" max="3650"
                               x-model="davomiylik"
                               @input="calcEnd()"
                               placeholder="Masalan: 90"
                               class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                            <i class="fa-regular fa-clock"></i>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Kiritilsa, tugash sanasi avtomatik to'ldiriladi</p>
                </label>

                {{-- Tugash sanasi --}}
                <label class="block">
                    <span>Tugash sanasi <span class="text-error">*</span></span>
                    <span class="relative mt-1.5 flex">
                        <input name="tugash_sanasi" type="text" required
                               value="{{ old('tugash_sanasi', $g?->tugash_sanasi?->format('Y-m-d')) }}"
                               x-init="$el._x_flatpickr = flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd.m.Y', allowInput: true })"
                               placeholder="Tanlang"
                               class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                            <i class="fa-regular fa-calendar"></i>
                        </span>
                    </span>
                </label>

                {{-- Protokol raqami --}}
                <label class="block">
                    <span>Protokol raqami</span>
                    <input name="protokol_raqami" value="{{ old('protokol_raqami', $g?->protokol_raqami) }}" placeholder="PI-98"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>
        </div>

        {{-- 6. Масъул шахслар --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Mas'ul shaxslar</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 sm:p-5">
                <label class="block">
                    <span>Komissiya raisi <span class="text-error">*</span></span>
                    <input name="komissiya_raisi_fio" required value="{{ old('komissiya_raisi_fio', $g?->komissiya_raisi_fio) }}"
                           placeholder="Taymurodov K.M"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
                <label class="block">
                    <span>Komissiya a'zosi</span>
                    <input name="komissiya_azosi_fio" value="{{ old('komissiya_azosi_fio', $g?->komissiya_azosi_fio) }}"
                           placeholder="Jumaev M.R"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
                <label class="block">
                    <span>Direktor <span class="text-error">*</span></span>
                    <input name="direktor_fio" required value="{{ old('direktor_fio', $g?->direktor_fio) }}"
                           placeholder="Shodiev Xusen Baxronovich"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>
        </div>

        {{-- 7. Баҳолар --}}
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Baholar</h4>
                </div>
            </div>
            <div class="space-y-4 p-4 sm:p-5">
                @foreach([
                    ['umumiy', 'Umumiy kurs / Общий курс'],
                    ['maxsus', 'Maxsus kurs / Специальный курс'],
                    ['ishlab_chiqarish', 'Ishlab chiqarish / Производственный'],
                ] as [$key, $label])
                <div>
                    <p class="text-xs+ uppercase tracking-wide text-slate-400 dark:text-navy-300 mb-2">{{ $label }}</p>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <input name="ball_{{ $key }}_oz" value="{{ old('ball_'.$key.'_oz', $g?->{'ball_'.$key.'_oz'}) }}" placeholder="a'lo"
                               class="form-input rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450">
                        <input name="ball_{{ $key }}_ru" value="{{ old('ball_'.$key.'_ru', $g?->{'ball_'.$key.'_ru'}) }}" placeholder="otlichno"
                               class="form-input rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('guvohnomalar.index') }}"
               class="btn min-w-[7rem] border border-slate-300 text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500">
                Bekor qilish
            </a>
            <button type="submit"
                    class="btn min-w-[8rem] bg-primary text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                <i class="fa-solid fa-file-pdf mr-2"></i>
                {{ $g ? 'Saqlash va qayta generatsiya' : 'Saqlash va pdf yaratish' }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // "Eski guvohnomadan namuna olish" — modal + form to'ldirish
    window.guvohnomaSamplePicker = function () {
        return {
            visible: false, loading: false, query: '',
            items: [], meta: { current_page: 1, last_page: 1, total: 0 },

            open() { this.visible = true; if (this.items.length === 0) this.load(1); },
            close() { this.visible = false; },

            async load(page) {
                this.loading = true;
                try {
                    const url = new URL("{{ route('guvohnomalar.samples') }}", window.location.origin);
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
                    const r = await fetch("{{ url('guvohnomalar') }}/" + id + "/sample-data", { headers: { 'Accept': 'application/json' } });
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