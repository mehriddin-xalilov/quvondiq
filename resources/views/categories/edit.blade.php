@extends('layouts.app')

@section('title', 'Kategoriyani Tahrirlash')
@section('header-title', 'Kategoriyani Tahrirlash')

@push('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
@endpush

@section('content')
<div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
    <div class="flex items-center space-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
            Tahrirlash: {{ $category->name }}
        </h2>
    </div>
    <div class="flex justify-center space-x-2">
        <a href="{{ route('categories.index') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            Bekor qilish
        </a>
        <button form="category-form" type="submit" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            Yangilash
        </button>
    </div>
</div>

<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
    <div class="col-span-12 lg:col-span-8">
        <div class="card p-4 sm:p-5">
            <form id="category-form" action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <label class="block">
                    <span class="font-medium text-slate-600 dark:text-navy-100">Ota Kategoriya</span>
                    <select class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" name="parent_id">
                        <option value="">Asosiy kategoriya</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="font-medium text-slate-600 dark:text-navy-100">Nomi</span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Kategoriya nomini kiriting" type="text" name="name" value="{{ old('name', $category->name) }}" required />
                    @error('name')
                        <span class="text-tiny+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block">
                    <span class="font-medium text-slate-600 dark:text-navy-100">Tavsif</span>
                    <textarea class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Kategoriya haqida qisqacha ma'lumot" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <span class="text-tiny+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <span class="font-medium text-slate-600 dark:text-navy-100">Kategoriya Rasmi</span>
                        
                        @if($category->icon)
                        <div class="my-2 flex items-center space-x-3 rounded-lg border border-slate-200 p-2 dark:border-navy-500">
                             <img src="{{ asset('storage/' . $category->icon) }}" class="h-16 w-16 rounded object-cover" alt="icon">
                             <div>
                                 <p class="font-medium text-slate-700 dark:text-navy-100">Joriy rasm</p>
                                 <p class="text-xs text-slate-400">O'zgartirish uchun pastdagi maydonga yuklang</p>
                             </div>
                        </div>
                        @endif

                        <div class="filepond fp-bordered fp-grid mt-1.5 [--fp-grid:2]">
                            <input type="file" 
                                   name="icon" 
                                   x-init="
                                       FilePond.registerPlugin(FilePondPluginImagePreview);
                                       FilePond.create($el, {
                                           storeAsFile: true,
                                           labelIdle: 'Rasmni yangilash uchun yuklang yoki sudrab tashlang',
                                           credits: false,
                                       });
                                   "
                            />
                        </div>
                    </div>

                    <label class="block">
                        <span class="font-medium text-slate-600 dark:text-navy-100">Tartib raqami</span>
                        <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="0" type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" />
                    </label>
                </div>
                
                <div class="flex items-center space-x-2 pt-2">
                    <input class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} />
                    <span class="font-medium text-slate-600 dark:text-navy-100">Faol</span>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include('partials.sidebar-menu-inventory')
