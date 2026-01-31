@extends('layouts.app')

@section('title', 'Buyurtma Tafsilotlari')
@section('page-title', 'Buyurtma #' . $telegramOrder->id)

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('telegram-orders.index') }}" class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
            <i class="fa-solid fa-arrow-left mr-2"></i> Orqaga
        </a>
        <div class="flex space-x-2">
            <form action="{{ route('telegram-orders.destroy', $telegramOrder->id) }}" method="POST" onsubmit="return confirm('Buyurtmani rad etasizmi?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90">
                    <i class="fa-solid fa-xmark mr-2"></i> Rad etish
                </button>
            </form>
            <a href="{{ route('sales.create_from_telegram', $telegramOrder->id) }}" class="btn bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                <i class="fa-solid fa-cart-plus mr-2"></i> Sotuvga o'tkazish
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <!-- Customer Info -->
        <div class="card p-4 sm:p-5 lg:col-span-1 h-fit">
            <h3 class="text-base font-medium text-slate-700 dark:text-navy-100 mb-4">Mijoz Ma'lumotlari</h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2 border-slate-100 dark:border-navy-600">
                    <span class="text-slate-400">Telegram:</span>
                    <span class="font-medium">{{ $telegramOrder->telegram_username }}</span>
                </div>
                <div class="flex justify-between border-b pb-2 border-slate-100 dark:border-navy-600">
                    <span class="text-slate-400">Ism:</span>
                    <span class="font-medium">{{ $telegramOrder->customer_name }}</span>
                </div>
                <div class="flex justify-between border-b pb-2 border-slate-100 dark:border-navy-600">
                    <span class="text-slate-400">Telefon:</span>
                    <span class="font-medium">{{ $telegramOrder->customer_phone }}</span>
                </div>
                <div class="flex justify-between border-b pb-2 border-slate-100 dark:border-navy-600">
                    <span class="text-slate-400">Manzil:</span>
                    <span class="font-medium text-right text-xs">{{ $telegramOrder->customer_address ?? '-' }}</span>
                </div>
                @if($telegramOrder->customer_notes)
                <div class="rounded bg-slate-100 p-3 dark:bg-navy-600">
                    <span class="text-xs text-slate-400">Izoh:</span>
                    <p class="text-sm">{{ $telegramOrder->customer_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="card p-4 sm:p-5 lg:col-span-2">
            <h3 class="text-base font-medium text-slate-700 dark:text-navy-100 mb-4">Buyurtma Tarkibi</h3>
            <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                <table class="is-hoverable w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-navy-500">
                            <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Mahsulot</th>
                            <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Narx</th>
                            <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100">Miqdor</th>
                            <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 text-right">Jami</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($telegramOrder->items as $item)
                        <tr class="border-b border-transparent hover:bg-slate-100 dark:hover:bg-navy-600">
                            <td class="px-4 py-3">{{ $item['name'] }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ number_format($item['price'], 0, '.', ' ') }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $item['quantity'] }}</td>
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-right">{{ number_format($item['subtotal'], 0, '.', ' ') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold text-slate-700 dark:text-navy-100 text-lg">
                            <td colspan="3" class="px-4 py-4 text-right">Jami:</td>
                            <td class="px-4 py-4 text-right text-primary">{{ number_format($telegramOrder->total, 0, '.', ' ') }} so'm</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
