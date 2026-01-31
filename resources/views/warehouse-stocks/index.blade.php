@extends('layouts.app')

@section('title', 'Ombor Qoldiqlari')
@section('header-title', 'Ombor Qoldiqlari')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-4 lg:gap-6">
        <div class="card p-4">
            <div class="flex items-center justify-between space-x-1">
                <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                    {{ $totalProducts }}
                </p>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <p class="mt-1 text-xs+ text-slate-400">Jami Mahsulotlar</p>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between space-x-1">
                <p class="text-xl font-semibold text-success dark:text-success-light">
                    {{ $inStock }}
                </p>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="mt-1 text-xs+ text-slate-400">Qoldiqda Bor</p>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between space-x-1">
                <p class="text-xl font-semibold text-warning dark:text-warning-light">
                    {{ $lowStock }}
                </p>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <p class="mt-1 text-xs+ text-slate-400">Kam Qoldiq</p>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between space-x-1">
                <p class="text-xl font-semibold text-error dark:text-error-light">
                    {{ $outOfStock }}
                </p>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="mt-1 text-xs+ text-slate-400">Tugagan</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-4 sm:p-5">
        <form method="GET" action="{{ route('warehouse-stocks.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div class="sm:col-span-2">
                <label class="block">
                    <span class="text-xs+ text-slate-400">Qidiruv</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Mahsulot nomi yoki kodi..." class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Kategoriya</span>
                    <select name="category_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="">Barchasi</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Holati</span>
                    <select name="stock_status" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="">Barchasi</option>
                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Qoldiqda Bor</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Kam Qoldiq</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Tugagan</option>
                    </select>
                </label>
            </div>

            <div class="flex items-end space-x-2 sm:col-span-4">
                <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    <i class="fa-solid fa-filter mr-2"></i> Filtr
                </button>
                <a href="{{ route('warehouse-stocks.index') }}" class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                    Tozalash
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Mahsulot
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Kategoriya
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Jami Miqdor
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Band
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Mavjud
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Holati
                        </th>
                        <th class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Oxirgi Harakat
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="flex items-center space-x-3">
                                <div class="avatar size-10">
                                    @if($stock->product->image_path)
                                        <img class="rounded-lg object-cover" src="{{ asset('storage/' . $stock->product->image_path) }}" alt="{{ $stock->product->name }}">
                                    @else
                                        <div class="is-initial rounded-lg bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-200">
                                            <i class="fa-solid fa-box"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700 dark:text-navy-100">{{ $stock->product->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $stock->product->code }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                                {{ $stock->product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <p class="font-medium">{{ number_format($stock->quantity, 2) }} {{ $stock->product->unit }}</p>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            @if($stock->reserved_quantity > 0)
                                <p class="text-warning">{{ number_format($stock->reserved_quantity, 2) }} {{ $stock->product->unit }}</p>
                            @else
                                <p class="text-slate-400">-</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <p class="font-medium">{{ number_format($stock->available_quantity, 2) }} {{ $stock->product->unit }}</p>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            @if($stock->quantity <= 0)
                                <div class="badge rounded-full bg-error/10 text-error dark:bg-error/15">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> Tugagan
                                </div>
                            @elseif($stock->quantity <= 10)
                                <div class="badge rounded-full bg-warning/10 text-warning dark:bg-warning/15">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Kam
                                </div>
                            @else
                                <div class="badge rounded-full bg-success/10 text-success dark:bg-success/15">
                                    <i class="fa-solid fa-circle-check mr-1"></i> Bor
                                </div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="text-xs">
                                @if($stock->last_stock_in_at)
                                    <p class="text-success">
                                        <i class="fa-solid fa-arrow-down mr-1"></i>
                                        {{ $stock->last_stock_in_at->format('d.m.Y H:i') }}
                                    </p>
                                @endif
                                @if($stock->last_stock_out_at)
                                    <p class="text-error">
                                        <i class="fa-solid fa-arrow-up mr-1"></i>
                                        {{ $stock->last_stock_out_at->format('d.m.Y H:i') }}
                                    </p>
                                @endif
                                @if(!$stock->last_stock_in_at && !$stock->last_stock_out_at)
                                    <p class="text-slate-400">-</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 sm:px-5 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-16 mx-auto text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="mt-4 text-slate-400 dark:text-navy-300">Hozircha qoldiq yo'q</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($stocks->hasPages())
        <div class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5">
            {{ $stocks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@include('partials.sidebar-menu-warehouse')
