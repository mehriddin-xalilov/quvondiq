@extends('layouts.app')

@section('title', 'Ombor Harakatlari')
@section('header-title', 'Ombor Harakatlari')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <!-- Filters -->
    <div class="card p-4 sm:p-5">
        <form method="GET" action="{{ route('stock-movements.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Mahsulot</span>
                    <select name="product_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="">Barchasi</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Turi</span>
                    <select name="type" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="">Barchasi</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Kirim</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Chiqim</option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Tuzatish</option>
                    </select>
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Sanadan</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Sanagacha</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    <i class="fa-solid fa-filter mr-2"></i> Filtr
                </button>
                <a href="{{ route('stock-movements.index') }}" class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                    Tozalash
                </a>
            </div>
        </form>
    </div>

    <!-- Header -->
    <div class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0">
        <h2 class="text-xl font-medium text-slate-700 dark:text-navy-50">
            Harakatlar Tarixi
        </h2>
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('stock-movements.export') }}" onclick="this.href='{{ route("stock-movements.export") }}' + window.location.search; return true;" class="btn bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                <i class="fa-solid fa-file-excel mr-2"></i> Excel
            </a>
            <a href="{{ route('stock-movements.create') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-plus mr-2"></i> Yangi Harakat
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Sana
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Mahsulot
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Turi
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Miqdor
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Oldin → Keyin
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Foydalanuvchi
                        </th>
                        <th class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Izoh
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <p class="font-medium">{{ $movement->movement_date->format('d.m.Y') }}</p>
                            <p class="text-xs text-slate-400">{{ $movement->movement_date->format('H:i') }}</p>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="flex items-center space-x-3">
                                <div class="avatar size-9">
                                    @if($movement->product->image)
                                        <img class="rounded-lg object-cover" src="{{ asset('storage/'.$movement->product->image) }}" alt="image"/>
                                    @else
                                        <div class="is-initial rounded-lg bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100 text-xs">
                                            {{ substr($movement->product->name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700 dark:text-navy-100">{{ $movement->product->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $movement->product->code }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            @if($movement->type === 'in')
                                <div class="badge rounded-full bg-success/10 text-success dark:bg-success/15">
                                    <i class="fa-solid fa-arrow-down mr-1"></i> Kirim
                                </div>
                            @elseif($movement->type === 'out')
                                <div class="badge rounded-full bg-error/10 text-error dark:bg-error/15">
                                    <i class="fa-solid fa-arrow-up mr-1"></i> Chiqim
                                </div>
                            @else
                                <div class="badge rounded-full bg-warning/10 text-warning dark:bg-warning/15">
                                    <i class="fa-solid fa-sliders mr-1"></i> Tuzatish
                                </div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <p class="font-medium" :class="{
                                'text-success': '{{ $movement->type }}' === 'in',
                                'text-error': '{{ $movement->type }}' === 'out',
                                'text-warning': '{{ $movement->type }}' === 'adjustment'
                            }">
                                @if($movement->type === 'in')
                                    +{{ number_format($movement->quantity, 2) }}
                                @elseif($movement->type === 'out')
                                    -{{ number_format($movement->quantity, 2) }}
                                @else
                                    {{ number_format($movement->quantity, 2) }}
                                @endif
                                {{ $movement->product->unit }}
                            </p>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="flex items-center space-x-2">
                                <span class="text-slate-400">{{ number_format($movement->quantity_before, 2) }}</span>
                                <i class="fa-solid fa-arrow-right text-xs text-slate-400"></i>
                                <span class="font-medium">{{ number_format($movement->quantity_after, 2) }}</span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <p class="text-sm">{{ $movement->user->name }}</p>
                        </td>
                        <td class="px-4 py-3 sm:px-5">
                            <p class="text-xs text-slate-400 line-clamp-2">{{ $movement->notes ?? '-' }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 sm:px-5 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-16 mx-auto text-slate-300 dark:text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="mt-4 text-slate-400 dark:text-navy-300">Hozircha harakatlar yo'q</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($movements->hasPages())
        <div class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5">
            {{ $movements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@include('partials.sidebar-menu-inventory')
