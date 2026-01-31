@extends('layouts.app')

@section('title', 'Kategoriyalar')
@section('header-title', 'Barcha Kategoriyalar')

@section('content')
<div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
    <div class="flex items-center space-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
            Kategoriyalar
        </h2>
    </div>
    <div class="flex justify-center space-x-2">
        @can('create-categories')
        <a href="{{ route('categories.export') }}" onclick="this.href='{{ route("categories.export") }}' + window.location.search; return true;" class="btn min-w-[7rem] bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90 dark:bg-success dark:hover:bg-success-focus dark:focus:bg-success-focus dark:active:bg-success/90">
            <i class="fa-solid fa-file-excel mr-2"></i> Excel
        </a>
        <a href="{{ route('categories.create') }}" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Yangi Kategoriya
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @forelse($categories as $category)
    <div class="card p-4 sm:p-5 hover:shadow-lg transition-shadow duration-200 cursor-pointer" onclick="window.location='{{ route('categories.show', $category) }}'">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 flex-1">
                <div class="avatar size-10">
                    @if($category->icon)
                        <img class="rounded-full bg-slate-200 object-cover" src="{{ asset('storage/' . $category->icon) }}" alt="avatar" />
                    @else
                        <div class="is-initial rounded-full bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100 uppercase">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="font-medium text-slate-700 dark:text-navy-100 line-clamp-1" title="{{ $category->name }}">
                        {{ $category->name }}
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-navy-300">
                        {{ $category->updated_at->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>
            
            <div x-data="usePopper({placement:'bottom-end',offset:4})" @click.outside="isShowPopper && (isShowPopper = false)" class="inline-flex" onclick="event.stopPropagation()">
                <button x-ref="popperRef" @click="isShowPopper = !isShowPopper" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>

                <div x-ref="popperRoot" class="popper-root" :class="isShowPopper && 'show'">
                    <div class="popper-box rounded-md border border-slate-150 bg-white py-1.5 font-inter dark:border-navy-500 dark:bg-navy-700">
                        <ul>
                            @can('edit-categories')
                            <li>
                                <a href="{{ route('categories.edit', $category) }}" class="flex h-8 items-center space-x-3 px-3 pr-8 font-medium tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Tahrirlash</span>
                                </a>
                            </li>
                            @endcan
                            @can('delete-categories')
                            <li>
                                <div class="my-1 h-px bg-slate-150 dark:bg-navy-500"></div>
                            </li>
                            <li>
                                <button @click="document.querySelector('#delete-modal-{{ $category->id }}').showModal()" type="button" class="flex h-8 w-full items-center space-x-3 px-3 pr-8 font-medium tracking-wide text-error outline-none transition-all hover:bg-error/20 focus:bg-error/20 dark:hover:bg-error/20 dark:focus:bg-error/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>O'chirish</span>
                                </button>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <p class="text-slate-600 dark:text-navy-100 line-clamp-2 min-h-[3rem]">
                {{ $category->description ?? 'Tavsif yo\'q' }}
            </p>
            <div class="flex items-center justify-between text-xs text-slate-400 dark:text-navy-300 mt-4">
                <span class="badge rounded-full {{ $category->is_active ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
                    {{ $category->is_active ? 'Faol' : 'Nofaol' }}
                </span>
                <span>
                    @if($category->parent)
                        Ota: {{ $category->parent->name }}
                    @else
                        Asosiy
                    @endif
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-10">
        <p class="text-slate-400 dark:text-navy-300">Hozircha kategoriyalar yo'q</p>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $categories->links('vendor.pagination.tailwind') }}
</div>

<!-- Delete Confirmation Modals -->
@foreach($categories as $category)
<dialog id="delete-modal-{{ $category->id }}" class="dialog rounded-lg bg-white dark:bg-navy-700">
    <div class="p-6">
        <div class="flex items-center justify-center">
            <div class="flex size-16 items-center justify-center rounded-full bg-error/10 dark:bg-error/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <h3 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                Kategoriyani o'chirish
            </h3>
            <p class="mt-2 text-slate-500 dark:text-navy-300">
                Rostdan ham <strong class="text-slate-700 dark:text-navy-100">{{ $category->name }}</strong> kategoriyasini o'chirmoqchimisiz?
            </p>
            @if($category->products && $category->products->count() > 0)
            <div class="mt-3 rounded-lg bg-warning/10 p-3 dark:bg-warning/15">
                <p class="text-sm text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Bu kategoriyada {{ $category->products->count() }} ta mahsulot mavjud!
                </p>
            </div>
            @endif
            @if($category->children && $category->children->count() > 0)
            <div class="mt-3 rounded-lg bg-warning/10 p-3 dark:bg-warning/15">
                <p class="text-sm text-warning">
                     Bu kategoriyaning {{ $category->children->count() }} ta ost-kategoriyasi mavjud!
                </p>
            </div>
            @endif
        </div>

        <div class="mt-6 flex space-x-2">
            <button @click="$el.closest('dialog').close()" class="btn flex-1 border border-slate-300 font-medium text-slate-700 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                Bekor qilish
            </button>
            <form method="POST" action="{{ route('categories.destroy', $category) }}" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn w-full bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90">
                    O'chirish
                </button>
            </form>
        </div>
    </div>
</dialog>
@endforeach
@endsection

@include('partials.sidebar-menu-inventory')
