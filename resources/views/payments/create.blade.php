@extends('layouts.app')

@section('title', 'Qarzni so\'ndirish')
@section('page-title', 'Qarzni so\'ndirish')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <div class="card p-4 sm:p-5">
        <form action="{{ route('payments.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Customer Selection -->
            <label class="block">
                <span>Mijoz <span class="text-error">*</span></span>
                <select name="customer_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" required 
                    @if($customer) onmousedown="(function(e){ e.preventDefault(); })(event, this)" @endif
                    >
                    <option value="" disabled {{ !$customer ? 'selected' : '' }}>Mijozni tanlang</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ ($customer && $customer->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->name }} (Qarz: {{ number_format($c->total_debt, 0, '.', ' ') }})
                        </option>
                    @endforeach
                    @if($customer && !$customers->contains('id', $customer->id))
                         <option value="{{ $customer->id }}" selected>
                            {{ $customer->name }} (Qarz: {{ number_format($customer->total_debt, 0, '.', ' ') }})
                        </option>
                    @endif
                </select>
                @if($customer)
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <p class="text-xs text-slate-500 mt-1">Ushbu to'lov <b>{{ $customer->name }}</b> uchun qilinmoqda.</p>
                @endif
                @error('customer_id') <span class="text-tiny text-error">{{ $message }}</span> @enderror
            </label>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Amount -->
                <label class="block">
                    <span>Summa <span class="text-error">*</span></span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                        type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" required placeholder="To'lov summasi" />
                    @if($customer)
                        <span class="text-xs text-warning">Jami qarz: {{ number_format($customer->total_debt, 0, '.', ' ') }} so'm</span>
                    @endif
                    @error('amount') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>

                <!-- Payment Method -->
                <label class="block">
                    <span>To'lov turi <span class="text-error">*</span></span>
                    <select name="payment_method" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                        <option value="cash" selected>Naqd</option>
                        <option value="card">Plastik karta</option>
                    </select>
                    @error('payment_method') <span class="text-tiny text-error">{{ $message }}</span> @enderror
                </label>
            </div>

             <!-- Notes -->
             <label class="block">
                <span>Izoh</span>
                <textarea rows="3" name="notes" placeholder="To'lov haqida izoh..." class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('notes') }}</textarea>
                @error('notes') <span class="text-tiny text-error">{{ $message }}</span> @enderror
            </label>
            
            <!-- Date (Optional, defaults to now) -->
            <!-- We can add date picker later if needed -->

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <a href="{{ $customer ? route('customers.show', $customer->id) : route('dashboard') }}" class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                    Bekor qilish
                </a>
                <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    To'lovni qabul qilish
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
