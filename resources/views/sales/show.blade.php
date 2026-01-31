@extends('layouts.app')

@section('title', 'Chek - ' . $sale->invoice_number)
@section('page-title', 'Chek - ' . $sale->invoice_number)

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <!-- Actions -->
    <div class="flex items-center justify-between">
        <div class="flex space-x-2">
            <a href="{{ route('sales.index') }}" class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                <i class="fa-solid fa-arrow-left mr-2"></i> Orqaga
            </a>

            @can('delete-sales')
            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90">
                    <i class="fa-solid fa-trash mr-2"></i> Bekor qilish
                </button>
            </form>
            @endcan
        </div>

        <button onclick="window.print()" class="btn bg-primary font-medium text-white hover:bg-primary-focus">
            <i class="fa-solid fa-print mr-2"></i> Chop etish
        </button>
    </div>

    <!-- Invoice -->
    <div id="invoice" class="card p-6 sm:p-8 print:shadow-none">
        <!-- Header -->
        <div class="flex items-start justify-between mb-8">
            <div>
                @if($shopInfo->logo_path)
                    <img src="{{ asset('storage/' . $shopInfo->logo_path) }}" alt="{{ $shopInfo->name }}" class="h-16 mb-2">
                @endif
                <h1 class="text-2xl font-bold text-slate-800 dark:text-navy-50">{{ $shopInfo->name }}</h1>
                <p class="text-sm text-slate-600 dark:text-navy-300">{{ $shopInfo->address }}</p>
                <p class="text-sm text-slate-600 dark:text-navy-300">Tel: {{ $shopInfo->phone }}</p>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-bold text-primary dark:text-accent">CHEK</h2>
                <p class="text-lg font-semibold mt-2">{{ $sale->invoice_number }}</p>
                <p class="text-sm text-slate-600 dark:text-navy-300">{{ $sale->sale_date->format('d.m.Y H:i') }}</p>
            </div>
        </div>

        <!-- Customer Info -->
        @if($sale->customer)
        <div class="mb-6 p-4 rounded-lg bg-slate-50 dark:bg-navy-600">
            <h3 class="font-semibold text-slate-800 dark:text-navy-50 mb-2">Mijoz ma'lumotlari</h3>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>
                    <span class="text-slate-600 dark:text-navy-300">Ism:</span>
                    <span class="font-medium ml-2">{{ $sale->customer->name }}</span>
                </div>
                <div>
                    <span class="text-slate-600 dark:text-navy-300">Telefon:</span>
                    <span class="font-medium ml-2">{{ $sale->customer->phone }}</span>
                </div>
                @if($sale->customer->address)
                <div class="col-span-2">
                    <span class="text-slate-600 dark:text-navy-300">Manzil:</span>
                    <span class="font-medium ml-2">{{ $sale->customer->address }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Items Table -->
        <div class="mb-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-slate-200 dark:border-navy-500">
                        <th class="pb-3 text-left text-sm font-semibold uppercase text-slate-800 dark:text-navy-50">№</th>
                        <th class="pb-3 text-left text-sm font-semibold uppercase text-slate-800 dark:text-navy-50">Mahsulot</th>
                        <th class="pb-3 text-center text-sm font-semibold uppercase text-slate-800 dark:text-navy-50">Miqdor</th>
                        <th class="pb-3 text-right text-sm font-semibold uppercase text-slate-800 dark:text-navy-50">Narx</th>
                        <th class="pb-3 text-right text-sm font-semibold uppercase text-slate-800 dark:text-navy-50">Jami</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $index => $item)
                    <tr class="border-b border-slate-100 dark:border-navy-600">
                        <td class="py-3 text-sm">{{ $index + 1 }}</td>
                        <td class="py-3">
                            <p class="font-medium">{{ $item->product->name }}</p>
                            <p class="text-xs text-slate-400">{{ $item->product->code }}</p>
                        </td>
                        <td class="py-3 text-center">
                            {{ number_format($item->quantity, 2) }} {{ $item->product->unit }}
                        </td>
                        <td class="py-3 text-right">
                            {{ number_format($item->price, 0, '.', ' ') }} so'm
                        </td>
                        <td class="py-3 text-right font-medium">
                            {{ number_format($item->subtotal, 0, '.', ' ') }} so'm
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex justify-end mb-6">
            <div class="w-full max-w-sm space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600 dark:text-navy-300">Oraliq jami:</span>
                    <span class="font-medium">{{ number_format($sale->subtotal, 0, '.', ' ') }} so'm</span>
                </div>
                
                @if($sale->discount > 0)
                <div class="flex justify-between text-sm text-error">
                    <span>Chegirma @if($sale->discount_percent > 0)({{ $sale->discount_percent }}%)@endif:</span>
                    <span class="font-medium">-{{ number_format($sale->discount, 0, '.', ' ') }} so'm</span>
                </div>
                @endif

                <div class="flex justify-between border-t-2 border-slate-200 dark:border-navy-500 pt-2 text-lg font-bold">
                    <span class="text-slate-800 dark:text-navy-50">JAMI:</span>
                    <span class="text-primary dark:text-accent">{{ number_format($sale->total, 0, '.', ' ') }} so'm</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="mb-6 p-4 rounded-lg bg-slate-50 dark:bg-navy-600">
            <h3 class="font-semibold text-slate-800 dark:text-navy-50 mb-3">To'lov ma'lumotlari</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-slate-600 dark:text-navy-300">To'lov turi:</span>
                    @if($sale->payment_type === 'cash')
                        <span class="badge rounded-full bg-success/10 text-success ml-2">Naqd</span>
                    @elseif($sale->payment_type === 'card')
                        <span class="badge rounded-full bg-info/10 text-info ml-2">Plastik</span>
                    @elseif($sale->payment_type === 'debt')
                        <span class="badge rounded-full bg-warning/10 text-warning ml-2">Qarz</span>
                    @else
                        <span class="badge rounded-full bg-secondary/10 text-secondary ml-2">Aralash</span>
                    @endif
                </div>

                @if($sale->paid_cash > 0)
                <div>
                    <span class="text-slate-600 dark:text-navy-300">Naqd to'landi:</span>
                    <span class="font-medium ml-2">{{ number_format($sale->paid_cash, 0, '.', ' ') }} so'm</span>
                </div>
                @endif

                @if($sale->paid_card > 0)
                <div>
                    <span class="text-slate-600 dark:text-navy-300">Plastik to'landi:</span>
                    <span class="font-medium ml-2">{{ number_format($sale->paid_card, 0, '.', ' ') }} so'm</span>
                </div>
                @endif

                @if($sale->debt_amount > 0)
                <div class="col-span-2">
                    <span class="text-slate-600 dark:text-navy-300">Qarz:</span>
                    <span class="font-medium text-warning ml-2">{{ number_format($sale->debt_amount, 0, '.', ' ') }} so'm</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if($sale->notes)
        <div class="mb-6">
            <h3 class="font-semibold text-slate-800 dark:text-navy-50 mb-2">Izoh:</h3>
            <p class="text-sm text-slate-600 dark:text-navy-300">{{ $sale->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="border-t border-slate-200 dark:border-navy-500 pt-6 text-center text-sm text-slate-600 dark:text-navy-300">
            <p>Xaridingiz uchun rahmat!</p>
            <p class="mt-1">Sotuvchi: {{ $sale->user->name }}</p>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice, #invoice * {
        visibility: visible;
    }
    #invoice {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
}
</style>
@endpush
@endsection
