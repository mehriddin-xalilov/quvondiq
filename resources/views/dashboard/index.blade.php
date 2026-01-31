@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-4 lg:gap-6">
    <!-- Total Customers -->
    <div class="card px-5 py-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs+ uppercase text-slate-400 dark:text-navy-300">Mijozlar</p>
                <div class="mt-1 flex items-baseline space-x-2">
                    <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">
                        {{ $stats['total_customers'] }}
                    </p>
                </div>
            </div>
            <div class="mask is-squircle flex size-10 shrink-0 items-center justify-center bg-warning/10">
                <svg class="size-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="card px-5 py-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs+ uppercase text-slate-400 dark:text-navy-300">Mahsulotlar</p>
                <div class="mt-1 flex items-baseline space-x-2">
                    <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">
                        {{ $stats['total_products'] }}
                    </p>
                </div>
            </div>
            <div class="mask is-squircle flex size-10 shrink-0 items-center justify-center bg-info/10">
                <svg class="size-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Today Sales -->
    <div class="card px-5 py-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs+ uppercase text-slate-400 dark:text-navy-300">Bugungi Savdo</p>
                <div class="mt-1 flex items-baseline space-x-2">
                    <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">
                        {{ $stats['today_sales'] }}
                    </p>
                </div>
                <p class="text-xs text-success">{{ number_format($stats['today_revenue'], 0, '.', ' ') }} so'm</p>
            </div>
            <div class="mask is-squircle flex size-10 shrink-0 items-center justify-center bg-success/10">
                <svg class="size-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Month Revenue -->
    <div class="card px-5 py-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs+ uppercase text-slate-400 dark:text-navy-300">Oylik Daromad</p>
                <div class="mt-1 flex items-baseline space-x-2">
                    <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">
                        {{ number_format($stats['month_revenue'], 0, '.', ' ') }}
                    </p>
                </div>
                <p class="text-xs text-slate-400">{{ $stats['month_sales'] }} ta savdo</p>
            </div>
            <div class="mask is-squircle flex size-10 shrink-0 items-center justify-center bg-primary/10 dark:bg-accent/10">
                <svg class="size-5 text-primary dark:text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Sales -->
<div class="mt-4 sm:mt-5 lg:mt-6">
    <div class="card">
        <div class="flex items-center justify-between px-4 py-3 sm:px-5">
            <h2 class="text-base font-medium tracking-wide text-slate-700 dark:text-navy-100">
                So'nggi Savdolar
            </h2>
        </div>
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            #
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Invoice
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Mijoz
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Jami
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Status
                        </th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                            Sana
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_sales as $sale)
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">{{ $loop->iteration }}</td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <span class="font-medium text-primary dark:text-accent-light">{{ $sale->invoice_number }}</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            {{ $sale->customer->name ?? 'N/A' }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            {{ number_format($sale->total, 0, '.', ' ') }} so'm
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            @if($sale->status === 'completed')
                                <span class="badge bg-success/10 text-success dark:bg-success/15">Yakunlangan</span>
                            @elseif($sale->status === 'pending')
                                <span class="badge bg-warning/10 text-warning dark:bg-warning/15">Kutilmoqda</span>
                            @else
                                <span class="badge bg-error/10 text-error dark:bg-error/15">Bekor qilingan</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            {{ $sale->sale_date->format('d.m.Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                            Hozircha savdolar yo'q
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
