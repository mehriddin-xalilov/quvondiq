@extends('layouts.app')

@section('title', 'Mijoz Tafsilotlari')
@section('page-title', 'Mijoz Tafsilotlari')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <!-- Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('customers.index') }}" class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
            <i class="fa-solid fa-arrow-left mr-2"></i> Orqaga
        </a>
        <div class="flex space-x-2">
            @if($customer->total_debt > 0)
            <a href="{{ route('payments.create', ['customer_id' => $customer->id]) }}" class="btn bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                <i class="fa-solid fa-money-bill-wave mr-2"></i> Qarzni to'lash
            </a>
            @endif
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Tahrirlash
            </a>
            @can('delete-customers')
            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Mijozni o\'chirishni tasdiqlaysizmi?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90">
                    <i class="fa-solid fa-trash mr-2"></i> O'chirish
                </button>
            </form>
            @endcan
        </div>
    </div>

    <!-- Info & Stats -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Customer Info -->
        <div class="card p-4 sm:p-5 col-span-1 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-slate-700 dark:text-navy-100">Mijoz ma'lumotlari</h3>
                @if($customer->is_regular)
                    <span class="badge rounded-full bg-success/10 text-success">Doimiy xaridor</span>
                @endif
            </div>
            <div class="mt-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-400">Ism:</span>
                    <span class="font-medium">{{ $customer->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Telefon:</span>
                    <span class="font-medium">{{ $customer->phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Telegram:</span>
                    <span class="font-medium">{{ $customer->telegram_username ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Manzil:</span>
                    <span class="font-medium text-right">{{ $customer->address ?? '-' }}</span>
                </div>
                @if($customer->notes)
                <div class="rounded-lg bg-slate-100 p-3 dark:bg-navy-600">
                    <p class="text-xs text-slate-500 dark:text-navy-200">Izoh:</p>
                    <p class="text-sm mt-1">{{ $customer->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Stats -->
        <div class="card p-4 sm:p-5 flex flex-col justify-center items-center text-center">
            <div class="mb-2 rounded-full bg-primary/10 p-3 text-primary dark:bg-accent/10 dark:text-accent-light">
                <i class="fa-solid fa-shopping-bag text-2xl"></i>
            </div>
            <p class="text-xs+ text-slate-400">Jami Xaridlar</p>
            <p class="text-xl font-semibold text-slate-700 dark:text-navy-100 mt-1">
                {{ number_format($customer->sales->sum('total'), 0, '.', ' ') }} so'm
            </p>
            <p class="text-xs text-slate-400 mt-1">{{ $customer->sales->count() }} ta chek</p>
        </div>

        <div class="card p-4 sm:p-5 flex flex-col justify-center items-center text-center">
            <div class="mb-2 rounded-full bg-warning/10 p-3 text-warning">
                <i class="fa-solid fa-circle-exclamation text-2xl"></i>
            </div>
            <p class="text-xs+ text-slate-400">Jami Qarz</p>
            <p class="text-xl font-semibold text-warning mt-1">
                {{ number_format($customer->total_debt, 0, '.', ' ') }} so'm
            </p>
            @if($customer->total_debt > 0)
                <p class="text-xs text-error mt-1">To'lanmagan</p>
            @else
                <p class="text-xs text-success mt-1">Qarz yo'q</p>
            @endif
        </div>
    </div>

    <!-- History Tabs -->
    <div class="card" x-data="{ activeTab: 'sales' }">
        <div class="flex flex-col sm:flex-row border-b border-slate-200 dark:border-navy-500">
            <div class="flex overflow-x-auto">
                <button @click="activeTab = 'sales'" 
                    :class="activeTab === 'sales' ? 'border-primary text-primary dark:border-accent dark:text-accent-light' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-navy-200 dark:hover:text-navy-100'"
                    class="btn shrink-0 rounded-none border-b-2 px-5 py-3 font-medium focus:bg-slate-300/20 active:bg-slate-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/20">
                    Xaridlar Tarixi
                </button>
                <button @click="activeTab = 'payments'" 
                    :class="activeTab === 'payments' ? 'border-primary text-primary dark:border-accent dark:text-accent-light' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-navy-200 dark:hover:text-navy-100'"
                    class="btn shrink-0 rounded-none border-b-2 px-5 py-3 font-medium focus:bg-slate-300/20 active:bg-slate-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/20">
                    To'lovlar Tarixi
                </button>
                <button @click="activeTab = 'debts'" 
                    :class="activeTab === 'debts' ? 'border-primary text-primary dark:border-accent dark:text-accent-light' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-navy-200 dark:hover:text-navy-100'"
                    class="btn shrink-0 rounded-none border-b-2 px-5 py-3 font-medium focus:bg-slate-300/20 active:bg-slate-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/20">
                    Qarzlar Tarixi
                </button>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <!-- Sales Tab -->
            <div x-show="activeTab === 'sales'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                    <table class="is-hoverable w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-navy-500">
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Sana</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Chek</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Turi</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Summa</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Qarz</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->sales as $sale)
                            <tr class="border-b border-transparent hover:bg-slate-100 dark:hover:bg-navy-600">
                                <td class="whitespace-nowrap px-4 py-3">{{ $sale->created_at->format('d.m.Y H:i') }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="font-medium text-primary hover:underline dark:text-accent-light">{{ $sale->invoice_number }}</a>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if($sale->payment_type === 'cash') <span class="badge rounded-full bg-success/10 text-success">Naqd</span>
                                    @elseif($sale->payment_type === 'card') <span class="badge rounded-full bg-info/10 text-info">Plastik</span>
                                    @elseif($sale->payment_type === 'debt') <span class="badge rounded-full bg-warning/10 text-warning">Qarz</span>
                                    @else <span class="badge rounded-full bg-secondary/10 text-secondary">Aralash</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">{{ number_format($sale->total, 0, '.', ' ') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-warning font-medium">{{ $sale->debt_amount > 0 ? number_format($sale->debt_amount, 0, '.', ' ') : '-' }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn size-7 rounded-full bg-slate-150 p-0 hover:bg-slate-200 dark:bg-navy-500 dark:hover:bg-navy-450">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-4 py-4 text-center text-slate-400">Ma'lumot yo'q</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payments Tab -->
            <div x-show="activeTab === 'payments'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                    <table class="is-hoverable w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-navy-500">
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Sana</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Summa</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Usul</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Izoh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->payments as $payment)
                            <tr class="border-b border-transparent hover:bg-slate-100 dark:hover:bg-navy-600">
                                <td class="whitespace-nowrap px-4 py-3">{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-success">+{{ number_format($payment->amount, 0, '.', ' ') }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if($payment->payment_method === 'cash') <span class="badge rounded-full bg-success/10 text-success">Naqd</span>
                                    @else <span class="badge rounded-full bg-info/10 text-info">Plastik</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500">{{ $payment->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-4 py-4 text-center text-slate-400">To'lovlar yo'q</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Debts Tab -->
            <div x-show="activeTab === 'debts'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                    <table class="is-hoverable w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-navy-500">
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">To'lov Sanasi</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Chek</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Qarz Summasi</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">To'langan</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Qolgan</th>
                                <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Holat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->debts as $debt)
                            <tr class="border-b border-transparent hover:bg-slate-100 dark:hover:bg-navy-600">
                                <td class="whitespace-nowrap px-4 py-3">{{ $debt->created_at->format('d.m.Y') }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <a href="{{ route('sales.show', $debt->sale_id) }}" class="font-medium text-primary hover:underline dark:text-accent-light">
                                        {{ $debt->sale->invoice_number ?? 'Chek #' . $debt->sale_id }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 font-medium">{{ number_format($debt->amount, 0, '.', ' ') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-success">{{ number_format($debt->paid_amount, 0, '.', ' ') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 font-bold text-error">{{ number_format($debt->amount - $debt->paid_amount, 0, '.', ' ') }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if($debt->status === 'paid')
                                        <span class="badge rounded-full bg-success/10 text-success">To'langan</span>
                                    @elseif($debt->status === 'partial')
                                        <span class="badge rounded-full bg-warning/10 text-warning">Qisman</span>
                                    @else
                                        <span class="badge rounded-full bg-error/10 text-error">To'lanmagan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-4 py-4 text-center text-slate-400">Qarzlar tarixi yo'q</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
