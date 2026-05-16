@csrf
@php $t = $template ?? null; @endphp

<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
    <div class="col-span-12 lg:col-span-8">
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid fa-file-word"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Shablon ma'lumotlari</h4>
                </div>
            </div>

            <div class="space-y-5 p-4 sm:p-5">
                <label class="block">
                    <span>Shablon nomi <span class="text-error">*</span></span>
                    <input name="name" type="text" required value="{{ old('name', $t?->name) }}"
                           placeholder="Masalan: KASBI markaz sertifikati"
                           class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                    @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>

                <label class="block">
                    <span>Hujjat turi <span class="text-error">*</span></span>
                    <select name="type" required class="mt-1.5 w-full"
                            x-init="$el._x_tom = new Tom($el, { allowEmptyOption: false })">
                        <option value="certificate" @selected(old('type', $t?->type) === 'certificate')>Sertifikat</option>
                        <option value="guvohnoma" @selected(old('type', $t?->type) === 'guvohnoma')>Guvohnoma</option>
                    </select>
                    @error('type')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </label>

                <label class="block">
                    <span>Tavsif</span>
                    <textarea name="description" rows="3"
                              placeholder="Qisqacha izoh (ixtiyoriy)"
                              class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('description', $t?->description) }}</textarea>
                </label>

                <div>
                    <span>.docx fayl {{ $t ? '(o\'zgartirish kerak bo\'lsa)' : '*' }}</span>
                    <div class="filepond fp-bordered mt-1.5">
                        <input type="file" name="file" accept=".docx" {{ $t ? '' : 'required' }}
                               x-init="$el._x_filepond = FilePond.create($el, {
                                   labelIdle: 'Faylni sudrab tashlang yoki <span class=\'filepond--label-action\'>tanlang</span>',
                                   acceptedFileTypes: ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                                   maxFileSize: '10MB',
                                   storeAsFile: true
                               })">
                    </div>
                    @error('file')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    @if($t)
                        <p class="text-xs text-slate-400 mt-2">
                            Hozirgi fayl: <span class="font-medium text-slate-600 dark:text-navy-100">{{ $t->original_filename }}</span>
                        </p>
                    @endif
                </div>

                <label class="inline-flex items-center space-x-2 pt-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           @checked(old('is_active', $t?->is_active ?? true))
                           class="form-switch h-6 w-11 rounded-full bg-slate-300 before:rounded-full before:bg-slate-50 checked:bg-primary checked:before:bg-white dark:bg-navy-900 dark:before:bg-navy-300 dark:checked:bg-accent">
                    <span>Faol</span>
                </label>
            </div>

            <div class="flex justify-end space-x-2 border-t border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <a href="{{ route('templates.index') }}"
                   class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500">
                    Bekor qilish
                </a>
                <button type="submit"
                        class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                    <i class="fa-solid fa-{{ $t ? 'save' : 'upload' }} mr-2"></i>
                    {{ $t ? 'Saqlash' : 'Yuklash' }}
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
                .docx fayl ichida dinamik maydonlarni
                <code class="bg-slate-100 dark:bg-navy-700 px-1 rounded">@verbatim{{maydon}}@endverbatim</code> ko'rinishida yozing.
            </p>
            <p class="text-xs+ text-slate-500 dark:text-navy-300 mt-2">Misol:</p>
            <ul class="text-xs+ space-y-1 mt-1 font-mono text-primary dark:text-accent-light">
                @verbatim
                <li>{{fio_uz}}</li>
                <li>{{boshlanish_sanasi}}</li>
                <li>{{kasb_uz}}</li>
                <li>{{raqam}}</li>
                @endverbatim
            </ul>
            <p class="text-xs+ text-slate-500 dark:text-navy-300 mt-3">
                Yuklab bo'lgach, shablon sahifasida aniqlangan maydonlar ro'yxatini ko'rishingiz mumkin.
            </p>
        </div>
    </div>
</div>