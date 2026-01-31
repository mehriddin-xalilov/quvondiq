@extends('layouts.app')

@section('title', 'Telegram Buyurtmalar')
@section('page-title', 'Telegram Buyurtmalar')

@section('content')
<div class="space-y-4 sm:space-y-5 lg:space-y-6">
    <!-- Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <h2 class="text-base font-medium text-slate-700 dark:text-navy-100">Kutilayotgan Buyurtmalar</h2>
            <span class="badge rounded-full bg-warning/10 text-warning">{{ $orders->total() }}</span>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('telegram-orders.export') }}" class="btn bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                <i class="fa-solid fa-file-excel mr-2"></i> Excel
            </a>
            <a href="{{ route('telegram-orders.index') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-rotate mr-2"></i> Yangilash
            </a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Vaqt</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Mijoz</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Telefon</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Summa</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Holat</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                            <p class="text-xs text-slate-400">{{ $order->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <span class="font-medium text-slate-700 dark:text-navy-100">{{ $order->customer_name }}</span>
                            <p class="text-xs text-slate-400">{{ $order->telegram_username }}</p>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">{{ $order->customer_phone }}</td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5 font-semibold text-success">
                            {{ number_format($order->total, 0, '.', ' ') }} so'm
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <span class="badge rounded-full bg-warning/10 text-warning">Kutilmoqda</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="flex space-x-2">
                                <a href="{{ route('telegram-orders.show', $order->id) }}" class="btn size-8 p-0 text-primary hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:text-accent-light dark:hover:bg-accent-light/20">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <form action="{{ route('telegram-orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Buyurtmani rad etasizmi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn size-8 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                            <i class="fa-brands fa-telegram text-4xl mb-3 text-slate-300"></i>
                            <p>Yangi buyurtmalar yo'q</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
