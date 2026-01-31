@extends('layouts.app')

@section('title', 'Xarajatni Tahrirlash')
@section('page-title', 'Xarajatni Tahrirlash')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <div class="card p-4 sm:p-5">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Title -->
                <label class="block">
                    <span>Xarajat Nomi <span class="text-error">*</span></span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                        type="text" name="title" value="{{ old('title', $expense->title) }}" required />
                    @error('title') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>

                <!-- Amount -->
                <label class="block">
                    <span>Summa <span class="text-error">*</span></span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                        type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $expense->amount) }}" required />
                    @error('amount') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Category -->
                <label class="block">
                    <span>Kategoriya <span class="text-error">*</span></span>
                    <select name="category" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $expense->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>

                <!-- Date -->
                <label class="block">
                    <span>Sana <span class="text-error">*</span></span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                        type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required />
                    @error('expense_date') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>
            </div>

            <!-- Payment Method -->
            <label class="block">
                <span>To'lov Turi <span class="text-error">*</span></span>
                <div class="flex space-x-4 mt-1.5">
                    <label class="inline-flex items-center space-x-2">
                        <input class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" 
                            type="radio" name="payment_method" value="cash" {{ $expense->payment_method == 'cash' ? 'checked' : '' }} />
                        <span>Naqd</span>
                    </label>
                    <label class="inline-flex items-center space-x-2">
                        <input class="form-radio is-basic size-5 rounded-full border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" 
                            type="radio" name="payment_method" value="card" {{ $expense->payment_method == 'card' ? 'checked' : '' }} />
                        <span>Plastik Karta</span>
                    </label>
                </div>
                @error('payment_method') <span class="text-tiny text-error">{{ $message }}</span> @enderror
            </label>

            <!-- Description -->
             <label class="block">
                <span>Izoh (Ixtiyoriy)</span>
                <textarea rows="3" name="description" class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('description', $expense->description) }}</textarea>
                @error('description') <span class="text-tiny text-error">{{ $message }}</span> @enderror
            </label>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2 pt-4">
                <a href="{{ route('expenses.index') }}" class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                    Bekor qilish
                </a>
                <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    Yangilash
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
