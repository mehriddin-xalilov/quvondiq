@extends('layouts.app')

@section('title', 'Mahsulotni Tahrirlash')
@section('header-title', 'Mahsulotni Tahrirlash')

@push('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
@endpush

@section('content')
<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6" x-data="{ activeStep: 1 }">
    <!-- Steps Navigation -->
    <div class="col-span-12 lg:col-span-4">
        <div class="card p-4 sm:p-5">
            <ol class="steps is-vertical line-space [--size:2.75rem] [--line:.5rem]">
                <li class="step space-x-4 pb-12 before:bg-slate-200 dark:before:bg-navy-500"
                    :class="activeStep > 1 ? 'before:bg-primary dark:before:bg-accent' : ''"
                    @click="activeStep = 1" style="cursor: pointer">
                    <div class="step-header mask is-hexagon bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100"
                         :class="activeStep >= 1 ? 'bg-primary text-white dark:bg-accent' : ''">
                        <i class="fa-solid fa-layer-group text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">1-qadam</p>
                        <h3 class="text-base font-medium" :class="activeStep >= 1 ? 'text-primary dark:text-accent-light' : 'text-slate-700 dark:text-navy-100'">
                            Umumiy Ma'lumot
                        </h3>
                    </div>
                </li>
                <li class="step space-x-4 pb-12 before:bg-slate-200 dark:before:bg-navy-500"
                    :class="activeStep > 2 ? 'before:bg-primary dark:before:bg-accent' : ''"
                    @click="activeStep = 2" style="cursor: pointer">
                    <div class="step-header mask is-hexagon bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100"
                         :class="activeStep >= 2 ? 'bg-primary text-white dark:bg-accent' : ''">
                        <i class="fa-solid fa-tags text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">2-qadam</p>
                        <h3 class="text-base font-medium" :class="activeStep >= 2 ? 'text-primary dark:text-accent-light' : 'text-slate-700 dark:text-navy-100'">
                            Narx va O'lchov
                        </h3>
                    </div>
                </li>
                <li class="step space-x-4 pb-12 before:bg-slate-200 dark:before:bg-navy-500"
                    :class="activeStep > 3 ? 'before:bg-primary dark:before:bg-accent' : ''"
                    @click="activeStep = 3" style="cursor: pointer">
                    <div class="step-header mask is-hexagon bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100"
                         :class="activeStep >= 3 ? 'bg-primary text-white dark:bg-accent' : ''">
                        <i class="fa-solid fa-warehouse text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">3-qadam</p>
                        <h3 class="text-base font-medium" :class="activeStep >= 3 ? 'text-primary dark:text-accent-light' : 'text-slate-700 dark:text-navy-100'">
                            Ombor va Zahira
                        </h3>
                    </div>
                </li>
                <li class="step space-x-4 before:bg-slate-200 dark:before:bg-navy-500"
                    :class="activeStep > 4 ? 'before:bg-primary dark:before:bg-accent' : ''"
                    @click="activeStep = 4" style="cursor: pointer">
                    <div class="step-header mask is-hexagon bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100"
                         :class="activeStep >= 4 ? 'bg-primary text-white dark:bg-accent' : ''">
                        <i class="fa-solid fa-image text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">4-qadam</p>
                        <h3 class="text-base font-medium" :class="activeStep >= 4 ? 'text-primary dark:text-accent-light' : 'text-slate-700 dark:text-navy-100'">
                            Rasm va Yakunlash
                        </h3>
                    </div>
                </li>
            </ol>
        </div>
    </div>

    <!-- Form Content -->
    <div class="col-span-12 lg:col-span-8">
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid" :class="{
                            'fa-layer-group': activeStep === 1,
                            'fa-tags': activeStep === 2,
                            'fa-warehouse': activeStep === 3,
                            'fa-image': activeStep === 4
                        }"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100" x-text="{
                        1: 'Umumiy Ma\'lumot',
                        2: 'Narx va O\'lchov Birligi',
                        3: 'Ombor va Zahira Parametrlari',
                        4: 'Rasm Yuklash va Saqlash'
                    }[activeStep]"></h4>
                </div>
            </div>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-5">
                @csrf
                @method('PUT')
                
                <!-- Step 1: General -->
                <div x-show="activeStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="space-y-4">
                        <label class="block">
                            <span>Mahsulot Nomi</span>
                            <div class="relative flex mt-1.5">
                                <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Mahsulot nomini kiriting" type="text" name="name" value="{{ old('name', $product->name) }}" required />
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <i class="fa-solid fa-box-open"></i>
                                </span>
                            </div>
                            @error('name') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                        </label>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span>Kategoriya</span>
                                <select class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" name="category_id">
                                    <option value="">Kategoriya tanlang</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @if($category->children)
                                            @foreach($category->children as $child)
                                                <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>&nbsp;&nbsp;-- {{ $child->name }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                            </label>

                            <label class="block">
                                <span>Kod / Artikul</span>
                                <div class="relative flex mt-1.5">
                                    <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Kod" type="text" name="code" value="{{ old('code', $product->code) }}" required />
                                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                        <i class="fa-solid fa-barcode"></i>
                                    </span>
                                </div>
                                @error('code') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                            </label>
                        </div>

                        <label class="block">
                            <span>Tavsif</span>
                            <textarea class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Mahsulot haqida batafsil ma'lumot" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        </label>
                    </div>
                </div>

                <!-- Step 2: Pricing -->
                <div x-show="activeStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span>Sotish Narxi</span>
                                <div class="relative flex mt-1.5">
                                    <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Narx" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required />
                                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                        <i class="fa-solid fa-money-bill-wave"></i>
                                    </span>
                                </div>
                                @error('price') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                            </label>

                            <label class="block">
                                <span>Tannarx (ixtiyoriy)</span>
                                <div class="relative flex mt-1.5">
                                    <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Tannarx" type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" />
                                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                        <i class="fa-solid fa-coins"></i>
                                    </span>
                                </div>
                            </label>
                        </div>
                        
                        <label class="block">
                            <span>O'lchov Birligi</span>
                            <div class="flex flex-wrap gap-4 mt-2">
                                <label class="inline-flex items-center space-x-2">
                                    <input class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" name="unit" type="radio" value="kg" {{ old('unit', $product->unit) == 'kg' ? 'checked' : '' }} />
                                    <span>Kilogram (kg)</span>
                                </label>
                                <label class="inline-flex items-center space-x-2">
                                    <input class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" name="unit" type="radio" value="dona" {{ old('unit', $product->unit) == 'dona' ? 'checked' : '' }} />
                                    <span>Dona</span>
                                </label>
                                <label class="inline-flex items-center space-x-2">
                                    <input class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" name="unit" type="radio" value="qop" {{ old('unit', $product->unit) == 'qop' ? 'checked' : '' }} />
                                    <span>Qop</span>
                                </label>
                                <label class="inline-flex items-center space-x-2">
                                    <input class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" name="unit" type="radio" value="litr" {{ old('unit', $product->unit) == 'litr' ? 'checked' : '' }} />
                                    <span>Litr</span>
                                </label>
                            </div>
                            @error('unit') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                        </label>
                    </div>
                </div>

                <!-- Step 3: Stock -->
                <div x-show="activeStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <div class="space-y-4">
                        <div class="alert flex rounded-lg bg-info/10 px-4 py-4 text-info dark:bg-info/15 sm:px-5">
                            <div class="flex flex-col space-y-1">
                                <span class="font-medium">Ombor Parametrlari</span>
                                <span class="text-xs">Ushbu parametrlar mahsulot qoldig'ini nazorat qilishda yordam beradi. Tizim qoldiq minimal darajadan tushganda ogohlantiradi.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span>Minimal Qoldiq</span>
                                <div class="relative flex mt-1.5">
                                    <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="50" type="number" step="0.01" name="min_stock_level" value="{{ old('min_stock_level', $product->min_stock_level) }}" required />
                                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                        <i class="fa-solid fa-arrow-down-short-wide"></i>
                                    </span>
                                </div>
                            </label>

                            <label class="block">
                                <span>Optimal Qoldiq</span>
                                <div class="relative flex mt-1.5">
                                    <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="500" type="number" step="0.01" name="optimal_stock_level" value="{{ old('optimal_stock_level', $product->optimal_stock_level) }}" required />
                                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                        <i class="fa-solid fa-arrows-to-dot"></i>
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Image & Confirm -->
                <div x-show="activeStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <div class="space-y-4">
                        <div>
                            <span class="font-medium text-slate-600 dark:text-navy-100">Mahsulot Rasmi</span>
                            
                            @if($product->image)
                            <div class="my-2 flex items-center space-x-3 rounded-lg border border-slate-200 p-2 dark:border-navy-500">
                                 <img src="{{ asset('storage/' . $product->image) }}" class="h-16 w-16 rounded object-cover" alt="mahsulot rasmi">
                                 <div>
                                     <p class="font-medium text-slate-700 dark:text-navy-100">Joriy rasm</p>
                                     <p class="text-xs text-slate-400">O'zgartirish uchun pastdagi maydonga yuklang</p>
                                 </div>
                            </div>
                            @endif

                            <div class="filepond fp-bordered fp-grid mt-1.5 [--fp-grid:2]">
                                <input type="file" 
                                       name="image" 
                                       x-init="
                                           FilePond.registerPlugin(FilePondPluginImagePreview);
                                           FilePond.create($el, {
                                               storeAsFile: true,
                                               labelIdle: 'Rasmni yangilash uchun yuklang yoki sudrab tashlang',
                                               credits: false,
                                               acceptedFileTypes: ['image/*'],
                                               imagePreviewHeight: 200
                                           });
                                       "
                                />
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 pt-2">
                            <input class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} />
                            <span class="font-medium text-slate-600 dark:text-navy-100">Mahsulot Faol (Sotuvda mavjud)</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-6 mt-4 border-t border-slate-200 dark:border-navy-500">
                    <button type="button" 
                            @click="activeStep > 1 ? activeStep-- : document.location='{{ route('products.index') }}'" 
                            class="btn space-x-2 bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewbox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span x-text="activeStep === 1 ? 'Bekor qilish' : 'Orqaga'"></span>
                    </button>

                    <button type="button" 
                            x-show="activeStep < 4" 
                            @click="activeStep++" 
                            class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                        <span>Keyingi</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewbox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <button type="submit" 
                            x-show="activeStep === 4" 
                            class="btn space-x-2 bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                        <span>Yangilash</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
