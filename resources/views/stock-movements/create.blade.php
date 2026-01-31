@extends('layouts.app')

@section('title', 'Yangi Ombor Harakati')
@section('header-title', 'Yangi Ombor Harakati')

@section('content')
<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6" x-data="stockMovementForm()">
    <!-- Steps Navigation -->
    <div class="col-span-12 lg:col-span-4">
        <div class="card p-4 sm:p-5">
            <ol class="steps is-vertical line-space [--size:2.75rem] [--line:.5rem]">
                <li class="step space-x-4 pb-12 before:bg-slate-200 dark:before:bg-navy-500"
                    :class="activeStep > 1 ? 'before:bg-primary dark:before:bg-accent' : ''"
                    @click="activeStep = 1" style="cursor: pointer">
                    <div class="step-header mask is-hexagon bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100"
                         :class="activeStep >= 1 ? 'bg-primary text-white dark:bg-accent' : ''">
                        <i class="fa-solid fa-box text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">1-qadam</p>
                        <h3 class="text-base font-medium" :class="activeStep >= 1 ? 'text-primary dark:text-accent-light' : 'text-slate-700 dark:text-navy-100'">
                            Mahsulot Tanlash
                        </h3>
                    </div>
                </li>
                <li class="step space-x-4 pb-12 before:bg-slate-200 dark:before:bg-navy-500"
                    :class="activeStep > 2 ? 'before:bg-primary dark:before:bg-accent' : ''"
                    @click="activeStep = 2" style="cursor: pointer">
                    <div class="step-header mask is-hexagon bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100"
                         :class="activeStep >= 2 ? 'bg-primary text-white dark:bg-accent' : ''">
                        <i class="fa-solid fa-arrows-left-right text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">2-qadam</p>
                        <h3 class="text-base font-medium" :class="activeStep >= 2 ? 'text-primary dark:text-accent-light' : 'text-slate-700 dark:text-navy-100'">
                            Harakat Turi
                        </h3>
                    </div>
                </li>
                <li class="step space-x-4 pb-12 before:bg-slate-200 dark:before:bg-navy-500"
                    :class="activeStep > 3 ? 'before:bg-primary dark:before:bg-accent' : ''"
                    @click="activeStep = 3" style="cursor: pointer">
                    <div class="step-header mask is-hexagon bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100"
                         :class="activeStep >= 3 ? 'bg-primary text-white dark:bg-accent' : ''">
                        <i class="fa-solid fa-calculator text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">3-qadam</p>
                        <h3 class="text-base font-medium" :class="activeStep >= 3 ? 'text-primary dark:text-accent-light' : 'text-slate-700 dark:text-navy-100'">
                            Miqdor
                        </h3>
                    </div>
                </li>
                <li class="step space-x-4 before:bg-slate-200 dark:before:bg-navy-500"
                    :class="activeStep > 4 ? 'before:bg-primary dark:before:bg-accent' : ''"
                    @click="activeStep = 4" style="cursor: pointer">
                    <div class="step-header mask is-hexagon bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-100"
                         :class="activeStep >= 4 ? 'bg-primary text-white dark:bg-accent' : ''">
                        <i class="fa-solid fa-file-lines text-base"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-slate-400 dark:text-navy-300">4-qadam</p>
                        <h3 class="text-base font-medium" :class="activeStep >= 4 ? 'text-primary dark:text-accent-light' : 'text-slate-700 dark:text-navy-100'">
                            Tafsilotlar
                        </h3>
                    </div>
                </li>
            </ol>
        </div>
    </div>

    <!-- Form Content -->
    <div class="col-span-12 lg:col-span-8">
        <div class="card">
            <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                <div class="flex items-center space-x-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                        <i class="fa-solid" :class="{
                            'fa-box': activeStep === 1,
                            'fa-arrows-left-right': activeStep === 2,
                            'fa-calculator': activeStep === 3,
                            'fa-file-lines': activeStep === 4
                        }"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100" x-text="{
                        1: 'Mahsulot Tanlash',
                        2: 'Harakat Turini Tanlang',
                        3: 'Miqdorni Kiriting',
                        4: 'Qo\'shimcha Ma\'lumotlar'
                    }[activeStep]"></h4>
                </div>
            </div>

            <form action="{{ route('stock-movements.store') }}" method="POST" class="p-4 sm:p-5">
                @csrf
                
                <!-- Step 1: Select Product -->
                <div x-show="activeStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="space-y-4">
                        <label class="block">
                            <span>Mahsulot</span>
                            <select x-model="selectedProduct" @change="updateCurrentStock()" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" name="product_id" required>
                                <option value="">Mahsulot tanlang</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->stock->quantity ?? 0 }}" data-unit="{{ $product->unit }}">
                                        {{ $product->name }} ({{ $product->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                        </label>

                        <div x-show="currentStock !== null" class="alert flex rounded-lg bg-info/10 px-4 py-4 text-info dark:bg-info/15">
                            <div class="flex flex-col space-y-1">
                                <span class="font-medium">Hozirgi Qoldiq</span>
                                <span class="text-2xl font-bold" x-text="currentStock + ' ' + currentUnit"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Type -->
                <div x-show="activeStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="in" x-model="movementType" class="peer sr-only" required>
                                <div class="rounded-lg border-2 border-slate-300 p-4 text-center transition-all hover:border-success/50 peer-checked:scale-105 peer-checked:border-[5px] peer-checked:border-success peer-checked:bg-success/20 peer-checked:shadow-xl peer-checked:shadow-success/30 dark:border-navy-450 dark:peer-checked:border-success dark:peer-checked:bg-success/20">
                                    <i class="fa-solid fa-arrow-down text-3xl" :class="movementType === 'in' ? 'text-success' : 'text-success/70'"></i>
                                    <h4 class="mt-2 font-medium" :class="movementType === 'in' ? 'text-success font-bold' : 'text-slate-700 dark:text-navy-100'">Kirim</h4>
                                    <p class="text-xs text-slate-400">Mahsulot qo'shish</p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="out" x-model="movementType" class="peer sr-only">
                                <div class="rounded-lg border-2 border-slate-300 p-4 text-center transition-all hover:border-error/50 peer-checked:scale-105 peer-checked:border-[5px] peer-checked:border-error peer-checked:bg-error/20 peer-checked:shadow-xl peer-checked:shadow-error/30 dark:border-navy-450 dark:peer-checked:border-error dark:peer-checked:bg-error/20">
                                    <i class="fa-solid fa-arrow-up text-3xl" :class="movementType === 'out' ? 'text-error' : 'text-error/70'"></i>
                                    <h4 class="mt-2 font-medium" :class="movementType === 'out' ? 'text-error font-bold' : 'text-slate-700 dark:text-navy-100'">Chiqim</h4>
                                    <p class="text-xs text-slate-400">Mahsulot chiqarish</p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="adjustment" x-model="movementType" class="peer sr-only">
                                <div class="rounded-lg border-2 border-slate-300 p-4 text-center transition-all hover:border-warning/50 peer-checked:scale-105 peer-checked:border-[5px] peer-checked:border-warning peer-checked:bg-warning/20 peer-checked:shadow-xl peer-checked:shadow-warning/30 dark:border-navy-450 dark:peer-checked:border-warning dark:peer-checked:bg-warning/20">
                                    <i class="fa-solid fa-sliders text-3xl" :class="movementType === 'adjustment' ? 'text-warning' : 'text-warning/70'"></i>
                                    <h4 class="mt-2 font-medium" :class="movementType === 'adjustment' ? 'text-warning font-bold' : 'text-slate-700 dark:text-navy-100'">Tuzatish</h4>
                                    <p class="text-xs text-slate-400">Qoldiqni to'g'rilash</p>
                                </div>
                            </label>
                        </div>
                        @error('type') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Step 3: Quantity -->
                <div x-show="activeStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <div class="space-y-4">
                        <label class="block">
                            <span x-text="movementType === 'adjustment' ? 'Yangi Qoldiq' : 'Miqdor'"></span>
                            <div class="relative flex mt-1.5">
                                <input x-model="quantity" @input="calculateAfter()" class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="0" type="number" step="0.01" min="0.01" name="quantity" required />
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <i class="fa-solid fa-hashtag"></i>
                                </span>
                            </div>
                            @error('quantity') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                        </label>

                        <div x-show="quantity > 0 && currentStock !== null" class="rounded-lg border-2 p-4" :class="{
                            'border-success bg-success/5': movementType === 'in',
                            'border-error bg-error/5': movementType === 'out',
                            'border-warning bg-warning/5': movementType === 'adjustment'
                        }">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-slate-400">Hozirgi</p>
                                    <p class="text-xl font-bold" x-text="currentStock + ' ' + currentUnit"></p>
                                </div>
                                <div>
                                    <i class="fa-solid fa-arrow-right text-2xl text-slate-400"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400">Keyin</p>
                                    <p class="text-xl font-bold" :class="{
                                        'text-success': movementType === 'in',
                                        'text-error': movementType === 'out',
                                        'text-warning': movementType === 'adjustment'
                                    }" x-text="quantityAfter + ' ' + currentUnit"></p>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <span class="text-sm font-medium" :class="{
                                    'text-success': movementType === 'in',
                                    'text-error': movementType === 'out',
                                    'text-warning': movementType === 'adjustment'
                                }" x-text="getChangeText()"></span>
                            </div>
                        </div>

                        <div x-show="movementType === 'out' && quantity > currentStock" class="alert flex rounded-lg bg-error/10 px-4 py-4 text-error dark:bg-error/15">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                            <span>Qoldiqda yetarli mahsulot yo'q!</span>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Details -->
                <div x-show="activeStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <div class="space-y-4">
                        <label class="block" x-show="movementType === 'in'">
                            <span>Birlik Narxi (ixtiyoriy)</span>
                            <div class="relative flex mt-1.5">
                                <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="0" type="number" step="0.01" min="0" name="price_per_unit" />
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <i class="fa-solid fa-money-bill"></i>
                                </span>
                            </div>
                            @error('price_per_unit') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                        </label>

                        <label class="block">
                            <span>Izoh / Sabab</span>
                            <textarea class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Harakat haqida qo'shimcha ma'lumot" name="notes" rows="4"></textarea>
                            @error('notes') <span class="text-tiny+ text-error">{{ $message }}</span> @enderror
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-6 mt-4 border-t border-slate-200 dark:border-navy-500">
                    <button type="button" 
                            @click="activeStep > 1 ? activeStep-- : document.location='{{ route('stock-movements.index') }}'" 
                            class="btn space-x-2 bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewbox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span x-text="activeStep === 1 ? 'Bekor qilish' : 'Orqaga'"></span>
                    </button>

                    <button type="button" 
                            x-show="activeStep < 4" 
                            @click="activeStep++" 
                            class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                        <span>Keyingi</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewbox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <button type="submit" 
                            x-show="activeStep === 4" 
                            class="btn space-x-2 bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90">
                        <span>Saqlash</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function stockMovementForm() {
    return {
        activeStep: 1,
        selectedProduct: '',
        currentStock: null,
        currentUnit: '',
        movementType: '',
        quantity: 0,
        quantityAfter: 0,

        updateCurrentStock() {
            const select = document.querySelector('select[name="product_id"]');
            const option = select.options[select.selectedIndex];
            if (option.value) {
                this.currentStock = parseFloat(option.dataset.stock);
                this.currentUnit = option.dataset.unit;
                this.calculateAfter();
            } else {
                this.currentStock = null;
                this.currentUnit = '';
            }
        },

        calculateAfter() {
            if (this.currentStock === null || !this.quantity) {
                this.quantityAfter = 0;
                return;
            }

            const qty = parseFloat(this.quantity) || 0;
            
            switch (this.movementType) {
                case 'in':
                    this.quantityAfter = this.currentStock + qty;
                    break;
                case 'out':
                    this.quantityAfter = this.currentStock - qty;
                    break;
                case 'adjustment':
                    this.quantityAfter = qty;
                    break;
                default:
                    this.quantityAfter = this.currentStock;
            }
        },

        getChangeText() {
            if (this.movementType === 'adjustment') {
                const diff = this.quantityAfter - this.currentStock;
                return diff > 0 ? `+${diff.toFixed(2)} ${this.currentUnit}` : `${diff.toFixed(2)} ${this.currentUnit}`;
            }
            const qty = parseFloat(this.quantity) || 0;
            return this.movementType === 'in' ? `+${qty} ${this.currentUnit}` : `-${qty} ${this.currentUnit}`;
        }
    }
}
</script>
@endsection

@include('partials.sidebar-menu-inventory')
