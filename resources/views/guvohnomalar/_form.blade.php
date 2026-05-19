@csrf
@php $g = $guvohnoma ?? null; @endphp

<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
    <div class="col-span-12 lg:col-span-4 lg:place-items-start">
        <div class="sticky top-24">
            <ol class="steps is-vertical line-space [--size:2.75rem] [--line:.5rem]">
                @foreach([
                    ['fa-id-card', '1-bo\'lim', 'Шаблон & рақам'],
                    ['fa-user', '2-bo\'lim', 'Ходим Ф.И.О.'],
                    ['fa-location-dot', '3-bo\'lim', 'Жойлашуви'],
                    ['fa-briefcase', '4-bo\'lim', 'Мутахассислик'],
                    ['fa-calendar', '5-bo\'lim', 'Сана & протокол'],
                    ['fa-users', '6-bo\'lim', 'Масъул шахслар'],
                    ['fa-star', '7-bo\'lim', 'Баҳолар'],
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
        <template x-teleport="body">
            <div x-show="visible" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4"
                 style="background: rgba(15,23,42,.6)" @keydown.escape.window="close()">
                <div class="card w-full max-w-3xl max-h-[85vh] flex flex-col" @click.outside="close()">
                    <div class="flex items-center justify-between border-b border-slate-200 p-4 dark:border-navy-500">
                        <h4 class="font-medium text-slate-700 dark:text-navy-100">Eski guvohnomalar</h4>
                        <button type="button" @click="close()" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="p-4 border-b border-slate-200 dark:border-navy-500">
                        <input x-model.debounce.300ms="query" @input="load(1)"
                               placeholder="Раqам, ФИО, мутахассислик..."
                               class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450">
                    </div>
                    <div class="flex-1 overflow-y-auto">
                        <template x-if="loading">
                            <div class="p-8 text-center text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Yuklanmoqda...</div>
                        </template>
                        <template x-if="!loading && items.length === 0">
                            <div class="p-8 text-center text-slate-400">Hech narsa topilmadi</div>
                        </template>
                        <table x-show="!loading && items.length > 0" class="w-full text-sm">
                            <thead class="bg-slate-100 dark:bg-navy-800 text-xs uppercase text-slate-500">
                                <tr>
                                    <th class="px-3 py-2 text-left">№</th>
                                    <th class="px-3 py-2 text-left">Ф.И.О.</th>
                                    <th class="px-3 py-2 text-left">Мутахассислик</th>
                                    <th class="px-3 py-2 text-left">Разряд</th>
                                    <th class="px-3 py-2 text-left">Сана</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in items" :key="row.id">
                                    <tr @click="pick(row.id)" class="cursor-pointer border-t border-slate-200 dark:border-navy-600 hover:bg-primary/5">
                                        <td class="px-3 py-2 font-mono" x-text="row.raqam"></td>
                                        <td class="px-3 py-2" x-text="row.fio"></td>
                                        <td class="px-3 py-2 text-slate-500" x-text="row.mutaxassislik"></td>
                                        <td class="px-3 py-2" x-text="row.razryad"></td>
                                        <td class="px-3 py-2 text-slate-500" x-text="row.sana"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div x-show="!loading && meta.last_page > 1" class="flex items-center justify-between border-t border-slate-200 p-3 dark:border-navy-500">
                        <button type="button" @click="load(meta.current_page - 1)" :disabled="meta.current_page <= 1"
                                class="btn space-x-1 border border-slate-300 disabled:opacity-50">
                            <i class="fa-solid fa-chevron-left"></i><span>Oldingi</span>
                        </button>
                        <span class="text-xs text-slate-500">Sahifa <span x-text="meta.current_page"></span> / <span x-text="meta.last_page"></span> (<span x-text="meta.total"></span>)</span>
                        <button type="button" @click="load(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page"
                                class="btn space-x-1 border border-slate-300 disabled:opacity-50">
                            <span>Keyingi</span><i class="fa-solid fa-chevron-right"></i>
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
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Шаблон ва рақам</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-5">
                <label class="block">
                    <span>Шаблон <span class="text-error">*</span></span>
                    <select name="template_id" required class="mt-1.5 w-full"
                            x-init="$el._x_tom = new Tom($el, { placeholder: '— Шаблонни танланг —' })">
                        <option value=""></option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" @selected(old('template_id') == $tpl->id)>{{ $tpl->name }}</option>
                        @endforeach
                    </select>
                    @error('template_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>

                <label class="block">
                    <span>Гувоҳнома рақами <span class="text-error">*</span></span>
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
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Ходим Ф.И.О.</h4>
                </div>
            </div>
            <div class="space-y-5 p-4 sm:p-5">
                <p class="text-xs+ uppercase tracking-wide text-slate-400 dark:text-navy-300">Кирилл алифбосида киритинг</p>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <label class="block">
                        <span>Фамилия <span class="text-error">*</span></span>
                        <input name="familiya_ru" required
                               value="{{ old('familiya_ru', $g?->familiya_ru ?? $g?->familiya_oz) }}"
                               placeholder="Иванов"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>
                    <label class="block">
                        <span>Исм <span class="text-error">*</span></span>
                        <input name="ism_ru" required
                               value="{{ old('ism_ru', $g?->ism_ru ?? $g?->ism_oz) }}"
                               placeholder="Иван"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>
                    <label class="block">
                        <span>Отасининг исми</span>
                        <input name="otasi_ismi_ru"
                               value="{{ old('otasi_ismi_ru', $g?->otasi_ismi_ru ?? $g?->otasi_ismi_oz) }}"
                               placeholder="Петрович"
                               class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    </label>
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
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Жойлашуви</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-5">
                <label class="block">
                    <span>Вилоят</span>
                    <select name="region_id" class="region-select mt-1.5 w-full">
                        <option value=""></option>
                        @foreach($regions as $r)
                            <option value="{{ $r->id }}" @selected(old('region_id', $g?->region_id) == $r->id)>{{ $r->name_uz }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span>Туман</span>
                    <select name="district_id" class="district-select mt-1.5 w-full">
                        <option value=""></option>
                        @foreach($districts as $d)
                            <option value="{{ $d->id }}" data-region="{{ $d->region_id }}"
                                    @selected(old('district_id', $g?->district_id) == $d->id)>{{ $d->name_uz }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span>Берилган жой (Кирилл)</span>
                    <input name="berilgan_joy_oz" value="{{ old('berilgan_joy_oz', $g?->berilgan_joy_oz) }}" placeholder="Қарши"
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
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Мутахассислик</h4>
                </div>
            </div>
            <div class="space-y-4 p-4 sm:p-5">
                <label class="block">
                    <span>Маълумотномадан (ихтиёрий)</span>
                    <select name="profession_id" class="mt-1.5 w-full"
                            x-init="$el._x_tom = new Tom($el, { placeholder: '— Танланг —' })">
                        <option value=""></option>
                        @foreach($professions as $p)
                            <option value="{{ $p->id }}" @selected(old('profession_id', $g?->profession_id) == $p->id)>{{ $p->name_uz }}</option>
                        @endforeach
                    </select>
                </label>

                <div class="grid grid-cols-1 gap-4">
                    <label class="block">
                        <span>Мутахассислик (Кирилл) <span class="text-error">*</span></span>
                        <textarea name="mutaxassislik_oz" required rows="2"
                                  placeholder="Пўлат ва темир-бетон конструкцияларни монтаж қилиш бўйича монтажчи"
                                  class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('mutaxassislik_oz', $g?->mutaxassislik_oz) }}</textarea>
                    </label>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <label class="block sm:col-span-2">
                            <span>Специальность (Русский)</span>
                            <textarea name="mutaxassislik_ru" rows="2"
                                      placeholder="Монтажник по монтажу..."
                                      class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('mutaxassislik_ru', $g?->mutaxassislik_ru) }}</textarea>
                        </label>
                        <label class="block">
                            <span>Разряд</span>
                            <input name="razryad" value="{{ old('razryad', $g?->razryad) }}" placeholder="5"
                                   class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
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
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Сана ва протокол</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-5">
                @foreach([
                    ['boshlanish_sanasi', 'Бошланиш санаси'],
                    ['tugash_sanasi', 'Тугаш санаси'],
                ] as [$name, $label])
                <label class="block">
                    <span>{{ $label }} <span class="text-error">*</span></span>
                    <span class="relative mt-1.5 flex">
                        <input name="{{ $name }}" type="text" required
                               value="{{ old($name, $g?->$name?->format('Y-m-d')) }}"
                               x-init="$el._x_flatpickr = flatpickr($el, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd.m.Y', allowInput: true })"
                               placeholder="Танланг"
                               class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                        <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                            <i class="fa-regular fa-calendar"></i>
                        </span>
                    </span>
                </label>
                @endforeach

                <label class="block sm:col-span-2">
                    <span>Протокол рақами</span>
                    <input name="protokol_raqami" value="{{ old('protokol_raqami', $g?->protokol_raqami) }}" placeholder="ПИ-98"
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
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Масъул шахслар</h4>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 sm:p-5">
                <label class="block">
                    <span>Комиссия раиси <span class="text-error">*</span></span>
                    <input name="komissiya_raisi_fio" required value="{{ old('komissiya_raisi_fio', $g?->komissiya_raisi_fio) }}"
                           placeholder="Таймуродов К.М"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
                <label class="block">
                    <span>Комиссия аъзоси</span>
                    <input name="komissiya_azosi_fio" value="{{ old('komissiya_azosi_fio', $g?->komissiya_azosi_fio) }}"
                           placeholder="Жумаев М.Р"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
                <label class="block">
                    <span>Директор <span class="text-error">*</span></span>
                    <input name="direktor_fio" required value="{{ old('direktor_fio', $g?->direktor_fio) }}"
                           placeholder="Шодиев Хусен Бахронович"
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
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Баҳолар</h4>
                </div>
            </div>
            <div class="space-y-4 p-4 sm:p-5">
                @foreach([
                    ['umumiy', 'Умумий курс / Общий курс'],
                    ['maxsus', 'Махсус курс / Специальный курс'],
                    ['ishlab_chiqarish', 'Ишлаб чиқариш / Производственный'],
                ] as [$key, $label])
                <div>
                    <p class="text-xs+ uppercase tracking-wide text-slate-400 dark:text-navy-300 mb-2">{{ $label }}</p>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <input name="ball_{{ $key }}_oz" value="{{ old('ball_'.$key.'_oz', $g?->{'ball_'.$key.'_oz'}) }}" placeholder="аъло"
                               class="form-input rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450">
                        <input name="ball_{{ $key }}_ru" value="{{ old('ball_'.$key.'_ru', $g?->{'ball_'.$key.'_ru'}) }}" placeholder="отлично"
                               class="form-input rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('guvohnomalar.index') }}"
               class="btn min-w-[7rem] border border-slate-300 text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500">
                Бекор қилиш
            </a>
            <button type="submit"
                    class="btn min-w-[8rem] bg-primary text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                <i class="fa-solid fa-file-circle-check mr-2"></i>
                {{ $g ? 'Сақлаш ва қайта генерация' : 'Сақлаш ва docx яратиш' }}
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

        const tomDistrict = new Tom(districtEl, { placeholder: '— Туман —', allowEmptyOption: true });
        const tomRegion = new Tom(regionEl, {
            placeholder: '— Вилоят —',
            allowEmptyOption: true,
            onChange(value) { filterDistricts(value); }
        });

        function filterDistricts(regionId) {
            const current = tomDistrict.getValue();
            tomDistrict.clear();
            tomDistrict.clearOptions();
            tomDistrict.addOption({ value: '', text: '— Туман —' });
            allDistricts
                .filter(d => !regionId || d.region === String(regionId))
                .forEach(d => tomDistrict.addOption({ value: d.value, text: d.text }));
            tomDistrict.refreshOptions(false);
            if (current && allDistricts.find(d => d.value === current && (!regionId || d.region === String(regionId)))) {
                tomDistrict.setValue(current);
            }
        }

        filterDistricts(tomRegion.getValue());
        const preSelected = allDistricts.find(d => d.selected);
        if (preSelected) tomDistrict.setValue(preSelected.value);
    });

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

                    // Tom Select (region, district, profession, template)
                    if (el._x_tom || (el.classList && (el.classList.contains('region-select') || el.classList.contains('district-select')))) {
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