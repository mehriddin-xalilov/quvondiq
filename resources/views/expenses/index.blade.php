@extends('layouts.app')

@section('title', 'Xarajatlar')
@section('page-title', 'Xarajatlar')

@section('content')
<div class="space-y-4 sm:space-y-5 lg:space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:gap-6">
        <div class="card p-4 sm:p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-medium tracking-wide text-slate-700 dark:text-navy-100">Bu oy ({{ date('m/Y') }})</h2>
                    <p class="mt-1 text-xs text-slate-400">Jami xarajat</p>
                </div>
                <div class="rounded-full bg-primary/10 p-3 text-primary dark:bg-accent/10 dark:text-accent-light">
                    <i class="fa-solid fa-calendar-days text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ number_format($stats['total_this_month'], 0, '.', ' ') }} so'm</p>
            </div>
        </div>
        <div class="card p-4 sm:p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-medium tracking-wide text-slate-700 dark:text-navy-100">Bugun</h2>
                    <p class="mt-1 text-xs text-slate-400">Jami xarajat</p>
                </div>
                <div class="rounded-full bg-warning/10 p-3 text-warning">
                    <i class="fa-solid fa-calendar-day text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ number_format($stats['total_today'], 0, '.', ' ') }} so'm</p>
            </div>
        </div>
        <div class="card p-4 sm:p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-medium tracking-wide text-slate-700 dark:text-navy-100">Jami (Filtr)</h2>
                    <p class="mt-1 text-xs text-slate-400">Tanlangan davr uchun</p>
                </div>
                <div class="rounded-full bg-success/10 p-3 text-success">
                    <i class="fa-solid fa-calculator text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-semibold text-slate-700 dark:text-navy-100">{{ number_format($stats['total_all'], 0, '.', ' ') }} so'm</p>
            </div>
        </div>
    </div>

    <!-- Actions & Filters -->
    <div class="flex flex-col justify-between space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
        <form action="{{ route('expenses.index') }}" method="GET" class="flex flex-wrap gap-3">
            <select name="category" class="form-select rounded-full border-slate-300 px-4 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                <option value="">Barcha Kategoriyalar</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input rounded-full border-slate-300 px-4 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Bosh sana">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input rounded-full border-slate-300 px-4 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Tugash sana">
            <button type="submit" class="btn rounded-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-filter mr-2"></i> Filtr
            </button>
            @if(request()->anyFilled(['category', 'date_from', 'date_to']))
                <a href="{{ route('expenses.index') }}" class="btn rounded-full bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                    Tozalash
                </a>
            @endif
        </form>
        <div class="flex space-x-2">
            <a href="{{ route('expenses.export') }}" onclick="this.href='{{ route("expenses.export") }}' + window.location.search; return true;" class="btn rounded-full bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                <i class="fa-solid fa-file-excel mr-2"></i> Excel
            </a>
            <a href="{{ route('expenses.create') }}" class="btn rounded-full bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                <i class="fa-solid fa-plus mr-2"></i> Xarajat Qo'shish
            </a>
        </div>
    </div>

    <!-- Expense Table -->
    <div class="card">
        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
                <thead>
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Sana</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Nomi</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Kategoriya</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Summa</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">To'lov Turi</th>
                        <th class="whitespace-nowrap px-4 py-3 font-semibold uppercase text-slate-800 dark:text-navy-100 lg:px-5">Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">{{ $expense->expense_date->format('d.m.Y') }}</td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <span class="font-medium text-slate-700 dark:text-navy-100">{{ $expense->title }}</span>
                            @if($expense->description)
                                <p class="text-xs text-slate-400">{{ Str::limit($expense->description, 30) }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <span class="badge rounded-full border border-secondary/30 bg-secondary/10 text-secondary dark:border-secondary-light/30 dark:bg-secondary-light/10 dark:text-secondary-light">
                                {{ $expense->category }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5 font-semibold text-error">
                            -{{ number_format($expense->amount, 0, '.', ' ') }} so'm
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            @if($expense->payment_method == 'cash') Naqd @else Plastik @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                            <div class="flex space-x-2">
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn size-8 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn size-8 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400">Xarajatlar topilmadi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $expenses->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
