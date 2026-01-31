@extends('layouts.app')

@section('title', 'Rolni Tahrirlash')
@section('page-title', 'Rolni Tahrirlash')

@include('partials.sidebar-menu-users')

@section('content')
<div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
    <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
            Rolni Tahrirlash: {{ $role->name }}
        </h2>
    </div>
    <div class="flex justify-center space-x-2">
        <a href="{{ route('settings.roles.index') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            Bekor qilish
        </a>
        <button form="role-form" type="submit" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            Yangilash
        </button>
    </div>
</div>

<form id="role-form" method="POST" action="{{ route('settings.roles.update', $role) }}">
    @csrf
    @method('PUT')
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
                                <input type="text" name="name" value="{{ old('name', $role->name) }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror" placeholder="Masalan: Manager" required>
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
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent category-{{ $category }}" {{ $role->hasPermissionTo($permission->name) || in_array($permission->name, old('permissions', [])) ? 'checked' : '' }} onchange="checkCategoryPermissions('{{ $category }}')">
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
                <div class="rounded-lg bg-slate-100 p-4 dark:bg-navy-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs+ text-slate-400 dark:text-navy-300">Foydalanuvchilar</p>
                            <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ $role->users->count() }}</p>
                        </div>
                        <div class="flex size-12 items-center justify-center rounded-full bg-primary/10 dark:bg-accent-light/15">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-primary dark:text-accent-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-600 dark:text-navy-200">
                        Bu rolga tayinlangan foydalanuvchilar soni
                    </p>
                </div>

                <div class="rounded-lg bg-slate-100 p-4 dark:bg-navy-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs+ text-slate-400 dark:text-navy-300">Ruxsatlar</p>
                            <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ $role->permissions->count() }}</p>
                        </div>
                        <div class="flex size-12 items-center justify-center rounded-full bg-success/10 dark:bg-success/15">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-600 dark:text-navy-200">
                        Ushbu rolga berilgan ruxsatlar soni
                    </p>
                </div>

                <div class="rounded-lg bg-warning/10 p-4 dark:bg-warning/15">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-xs+ text-slate-600 dark:text-navy-200">
                            Ruxsatlarni o'zgartirishdan oldin, bu rol foydalanuvchilarga qanday ta'sir qilishini tekshiring.
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
        }
    }
    
    // Initial check on page load
    document.addEventListener('DOMContentLoaded', () => {
        const categories = @json($permissions->keys());
        categories.forEach(category => {
            checkCategoryPermissions(category);
        });
    });
</script>
@endsection
