@extends('layouts.app')

@section('title', 'Sotuvlar')
@section('page-title', 'Sotuvlar')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-5 lg:gap-6">
        <div class="card p-4">
            <div class="flex items-center justify-between space-x-1">
                <p class="text-xl font-semibold text-success dark:text-success-light">
                    {{ number_format($todaySales, 0, '.', ' ') }} so'm
                </p>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="mt-1 text-xs+ text-slate-400">Bugungi sotuv</p>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between space-x-1">
                <p class="text-xl font-semibold text-primary dark:text-accent-light">
                    {{ number_format($monthSales, 0, '.', ' ') }} so'm
                </p>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <p class="mt-1 text-xs+ text-slate-400">Oylik sotuv</p>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between space-x-1">
                <p class="text-xl font-semibold text-warning dark:text-warning-light">
                    {{ number_format($totalDebt, 0, '.', ' ') }} so'm
                </p>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="mt-1 text-xs+ text-slate-400">Jami qarz</p>
        </div>
    </div>

    <!-- New Sale Button -->
    @can('create-sales')
    <div class="flex justify-end space-x-2">
        <a href="{{ route('sales.export') }}" onclick="this.href='{{ route("sales.export") }}' + window.location.search; return true;" class="btn bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
            <i class="fa-solid fa-file-excel mr-2"></i> Excel
        </a>
        <a href="{{ route('sales.create') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
            <i class="fa-solid fa-plus mr-2"></i> Yangi Sotuv
        </a>
    </div>
    @endcan

    <!-- Filters -->
    <div class="card p-4 sm:p-5">
        <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-5">
            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Boshlanish</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2">
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Tugash</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2">
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Mijoz</span>
                    <select name="customer_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 dark:border-navy-450 dark:bg-navy-700">
                        <option value="">Barchasi</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">To'lov turi</span>
                    <select name="payment_type" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 dark:border-navy-450 dark:bg-navy-700">
                        <option value="">Barchasi</option>
                        <option value="cash" {{ request('payment_type') == 'cash' ? 'selected' : '' }}>Naqd</option>
                        <option value="card" {{ request('payment_type') == 'card' ? 'selected' : '' }}>Plastik</option>
                        <option value="debt" {{ request('payment_type') == 'debt' ? 'selected' : '' }}>Qarz</option>
                        <option value="mixed" {{ request('payment_type') == 'mixed' ? 'selected' : '' }}>Aralash</option>
                    </select>
                </label>
            </div>

            <div>
                <label class="block">
                    <span class="text-xs+ text-slate-400">Holati</span>
                    <select name="status" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 dark:border-navy-450 dark:bg-navy-700">
                        <option value="">Barchasi</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Yakunlangan</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Bekor qilingan</option>
                    </select>
                </label>
            </div>

            <div class="flex items-end space-x-2 sm:col-span-5">
                <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus">
                    <i class="fa-solid fa-filter mr-2"></i> Filtr
                </button>
                <a href="{{ route('sales.index') }}" class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                    Tozalash
                </a>
            </div>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="card" x-data="{
        deleteSale(url, el) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                }
            }).then(async r => {
                const data = await r.json();
                if (r.ok) {
                    if (typeof $notification === 'function') $notification({text: data.message, variant: 'success', position: 'right-top'});
                    el.closest('tr').remove();
                } else {
                     if (typeof $notification === 'function') $notification({text: data.message || 'Xatolik', variant: 'error', position: 'right-top'});
                }
            }).catch(e => {
                console.error(e);
                if (typeof $notification === 'function') $notification({text: 'Tizim xatoligi', variant: 'error', position: 'right-top'});
            });
        }
    }">
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Chek №
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Sana
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Mijoz
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Jami
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            To'lov
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Holat
                        </th>
                        <th class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Amallar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <a href="{{ route('sales.show', $sale->id) }}" class="font-medium text-primary hover:text-primary-focus dark:text-accent-light dark:hover:text-accent">
                                {{ $sale->invoice_number }}
                            </a>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            {{ $sale->sale_date->format('d.m.Y H:i') }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            {{ $sale->customer->name ?? 'Oddiy xaridor' }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <p class="font-medium">{{ number_format($sale->total, 0, '.', ' ') }} so'm</p>
                            @if($sale->debt_amount > 0)
                                <p class="text-xs text-warning">Qarz: {{ number_format($sale->debt_amount, 0, '.', ' ') }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            @if($sale->payment_type === 'cash')
                                <span class="badge rounded-full bg-success/10 text-success">Naqd</span>
                            @elseif($sale->payment_type === 'card')
                                <span class="badge rounded-full bg-info/10 text-info">Plastik</span>
                            @elseif($sale->payment_type === 'debt')
                                <span class="badge rounded-full bg-warning/10 text-warning">Qarz</span>
                            @else
                                <span class="badge rounded-full bg-secondary/10 text-secondary">Aralash</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            @if($sale->status === 'completed')
                                <span class="badge rounded-full bg-success/10 text-success">Yakunlangan</span>
                            @else
                                <span class="badge rounded-full bg-error/10 text-error">Bekor qilingan</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="flex space-x-2">
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                @can('delete-sales')
                                @if($sale->status === 'completed')
                                    <button type="button" @click="deleteSale('{{ route('sales.destroy', $sale->id) }}', $el)" class="btn size-8 rounded-full p-0 hover:bg-error/20 focus:bg-error/20 active:bg-error/25 text-error">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 sm:px-5 text-center">
                            <i class="fa-solid fa-receipt text-4xl text-slate-300 dark:text-navy-400"></i>
                            <p class="mt-2 text-slate-400 dark:text-navy-300">Hozircha sotuvlar yo'q</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@include('partials.sidebar-menu-sales')
