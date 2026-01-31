@extends('layouts.app')

@section('title', $category->name)
@section('header-title', $category->name)

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    
    <!-- Category Header -->
    <div class="card p-4 sm:p-5">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('categories.index') }}" class="btn size-10 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="avatar size-12">
                    @if($category->icon)
                        <img class="rounded-full bg-slate-200 object-cover" src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" />
                    @else
                        <div class="is-initial rounded-full bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100 uppercase text-xl">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-xl font-medium text-slate-700 dark:text-navy-50">
                        {{ $category->name }}
                    </h2>
                    <p class="text-sm text-slate-400 dark:text-navy-300">
                        {{ $category->description ?? 'Tavsif yo\'q' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="badge rounded-full {{ $category->is_active ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
                    {{ $category->is_active ? 'Faol' : 'Nofaol' }}
                </span>
                @can('edit-categories')
                <a href="{{ route('categories.edit', $category) }}" class="btn size-10 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Products Header & Controls -->
    <div class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0">
        <div class="flex items-center space-x-1">
            <h3 class="text-lg font-medium text-slate-700 dark:text-navy-50">
                Mahsulotlar
            </h3>
            <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                {{ $products->total() }}
            </div>
        </div>
        <div class="flex justify-center space-x-2">
            @can('create-products')
            <a href="{{ route('products.create', ['category_id' => $category->id]) }}" 
               class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-plus mr-2"></i> Yangi Mahsulot
            </a>
            @endcan
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Mahsulot
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Narx
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Qoldiq
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Status
                        </th>
                        <th class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Amallar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="flex items-center space-x-4">
                                <div class="avatar size-9">
                                    @if($product->image)
                                        <img class="rounded-lg object-cover" src="{{ asset('storage/'.$product->image) }}" alt="image"/>
                                    @else
                                        <div class="is-initial rounded-lg bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100 text-xs">
                                            {{ substr($product->name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700 dark:text-navy-100">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $product->code }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <p class="font-medium">{{ number_format($product->price, 0, '.', ' ') }} so'm</p>
                            @if($product->cost_price)
                                <p class="text-xs text-slate-400">Tan: {{ number_format($product->cost_price, 0, '.', ' ') }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="badge space-x-2.5 px-0 text-slate-800 dark:text-navy-100">
                                <div class="size-2 rounded-full {{ $product->is_low_stock ? 'bg-error' : 'bg-success' }}"></div>
                                <span>{{ $product->stock->quantity ?? 0 }} {{ $product->unit }}</span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="badge rounded-full {{ $product->is_active ? 'bg-success/10 text-success dark:bg-success/15' : 'bg-error/10 text-error dark:bg-error/15' }}">
                                {{ $product->is_active ? 'Faol' : 'Nofaol' }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="flex space-x-2">
                                @can('edit-products')
                                <a href="{{ route('products.edit', $product->id) }}" class="btn size-8 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @endcan
                                @can('delete-products')
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn size-8 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 sm:px-5 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-16 mx-auto text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="mt-4 text-slate-400 dark:text-navy-300">Bu kategoriyada hozircha mahsulotlar yo'q</p>
                            @can('create-products')
                            <a href="{{ route('products.create', ['category_id' => $category->id]) }}" class="btn mt-4 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                <i class="fa-solid fa-plus mr-2"></i> Birinchi Mahsulotni Qo'shing
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@include('partials.sidebar-menu-inventory')
