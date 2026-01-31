@extends('layouts.app')

@section('title', 'Yangi Rol')
@section('page-title', 'Yangi Rol Yaratish')

@include('partials.sidebar-menu-users')

@section('content')
<div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
    <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
            Yangi Rol
        </h2>
    </div>
    <div class="flex justify-center space-x-2">
        <a href="{{ route('settings.roles.index') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            Bekor qilish
        </a>
        <button form="role-form" type="submit" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            Saqlash
        </button>
    </div>
</div>

<form id="role-form" method="POST" action="{{ route('settings.roles.store') }}">
    @csrf
    <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="card">
                <div class="tabs flex flex-col">
                    <div class="is-scrollbar-hidden overflow-x-auto">
                        <div class="border-b-2 border-slate-150 dark:border-navy-500">
                            <div class="tabs-list -mb-0.5 flex">
                                <button type="button" class="btn h-14 shrink-0 space-x-2 rounded-none border-b-2 border-primary px-4 font-medium text-primary dark:border-accent dark:text-accent-light sm:px-5">
                                    <i class="fa-solid fa-layer-group text-base"></i>
                                    <span>Asosiy Ma'lumotlar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content p-4 sm:p-5">
                        <div class="space-y-5">
                            <label class="block">
                                <span class="font-medium text-slate-600 dark:text-navy-100">Rol Nomi</span>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror" placeholder="Masalan: Manager" required>
                                @error('name')
                                    <span class="text-tiny+ text-error">{{ $message }}</span>
                                @enderror
                            </label>

                            <div>
                                <span class="font-medium text-slate-600 dark:text-navy-100">Ruxsatlar</span>
                                <div class="mt-3 space-y-4">
                                    @foreach($permissions as $category => $categoryPermissions)
                                    <div class="rounded-lg border border-slate-200 p-4 dark:border-navy-500">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-medium text-slate-700 dark:text-navy-100 capitalize">
                                                {{ ucfirst($category) }}
                                            </h4>
                                            <label class="inline-flex items-center space-x-2">
                                                <input type="checkbox" id="select-all-{{ $category }}" class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" onchange="toggleCategoryPermissions(this, '{{ $category }}')">
                                                <span class="text-xs+ text-slate-600 dark:text-navy-200">Hammasini tanlash</span>
                                            </label>
                                        </div>
                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                            @foreach($categoryPermissions as $permission)
                                            <label class="inline-flex items-center space-x-2">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent category-{{ $category }}" {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }} onchange="checkCategoryPermissions('{{ $category }}')">
                                                <span class="text-sm text-slate-600 dark:text-navy-200">{{ $permission->name }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="card space-y-5 p-4 sm:p-5">
                <div class="rounded-lg bg-info/10 p-4 dark:bg-info/15">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs+ text-slate-600 dark:text-navy-200">
                            Rol yaratilgandan so'ng, foydalanuvchilarga tayinlash mumkin bo'ladi.
                        </p>
                    </div>
                </div>

                <div class="rounded-lg bg-warning/10 p-4 dark:bg-warning/15">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-xs+ text-slate-600 dark:text-navy-200">
                            Ruxsatlarni ehtiyotkorlik bilan tanlang. Noto'g'ri ruxsatlar xavfsizlik muammolariga olib kelishi mumkin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function toggleCategoryPermissions(checkbox, category) {
        const categoryCheckboxes = document.querySelectorAll(`.category-${category}`);
        categoryCheckboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
    }

    function checkCategoryPermissions(category) {
        const categoryCheckboxes = document.querySelectorAll(`.category-${category}`);
        const selectAllCheckbox = document.getElementById(`select-all-${category}`);
        const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allChecked;
            // Indeterminate state logic could be added here if desired
            // selectAllCheckbox.indeterminate = !allChecked && Array.from(categoryCheckboxes).some(cb => cb.checked);
        }
    }
    
    // Initial check on page load (useful for validation errors redirect)
    document.addEventListener('DOMContentLoaded', () => {
        const categories = @json($permissions->keys());
        categories.forEach(category => {
            checkCategoryPermissions(category);
        });
    });
</script>
@endsection
