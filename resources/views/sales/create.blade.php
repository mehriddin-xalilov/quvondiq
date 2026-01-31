@extends('layouts.app')

@section('title', 'Yangi Sotuv')
@section('page-title', 'Yangi Sotuv (POS)')

@section('content')
@if ($errors->any())
    <div class="alert flex rounded-lg border border-error px-4 py-4 text-error dark:border-error-light dark:text-error-light mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <div class="ml-3">
            <h4 class="font-bold">Xatolik yuz berdi!</h4>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<!-- Toast Notification -->
<div 
    x-data="{ notification: { show: false, text: '', variant: 'info' } }"
    @notify.window="notification = { show: true, text: $event.detail.text, variant: $event.detail.variant }; setTimeout(() => notification.show = false, 4000)"
    x-show="notification.show" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="fixed top-20 right-4 z-50 min-w-[300px] max-w-sm rounded-lg p-4 text-white shadow-lg"
    :class="{
        'bg-info': notification.variant === 'info',
        'bg-success': notification.variant === 'success', 
        'bg-error': notification.variant === 'error',
        'bg-warning': notification.variant === 'warning'
    }"
    style="display: none;"
>
    <div class="flex items-center">
        <i class="mr-2 text-lg" :class="{
            'fa-solid fa-circle-info': notification.variant === 'info',
            'fa-solid fa-check-circle': notification.variant === 'success',
            'fa-solid fa-triangle-exclamation': notification.variant === 'error' || notification.variant === 'warning'
        }"></i>
        <span x-text="notification.text" class="font-medium"></span>
    </div>
</div>

<div x-data="posSystem()" x-init="updateCart()" class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">
    <!-- Left Side: Products & Cart -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Customer Selection -->
        <div class="card p-4">
            <label class="block">
                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Mijoz (ixtiyoriy)</span>
                <!-- Show pre-filled customer info if active -->
                <div x-show="newCustomerName" class="mb-2 p-2 bg-info/10 text-info rounded border border-info/20 text-sm">
                    <i class="fa-solid fa-user-plus mr-1"></i> Yangi mijoz: <strong x-text="newCustomerName"></strong>
                    <br>
                    <span x-text="newCustomerPhone" class="text-xs ml-5"></span>
                </div>

                <select x-model="customerId" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                    <option value="">Oddiy xaridor</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <!-- Product Search & Selection -->
        <div class="card p-4">
            <div class="mb-4">
                <label class="block">
                    <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Mahsulot qidirish</span>
                    <input x-model="searchQuery" @input="filterProducts" type="text" placeholder="Mahsulot nomi yoki kodi..." class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                </label>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 max-h-96 overflow-y-auto">
                <template x-for="product in filteredProducts" :key="product.id">
                    <button @click="addToCart(product)" type="button" class="card p-3 hover:shadow-lg transition-shadow duration-200 text-left">
                        <div class="flex flex-col items-center">
                            <div class="avatar size-16 mb-2">
                                <img x-show="product.image" :src="'/storage/' + product.image" :alt="product.name" class="rounded-lg object-cover">
                                <div x-show="!product.image" class="is-initial rounded-lg bg-slate-200 text-slate-500 dark:bg-navy-500 dark:text-navy-200">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                            </div>
                            <p class="text-xs font-medium text-center line-clamp-2" x-text="product.name"></p>
                            <p class="text-xs text-primary font-semibold mt-1" x-text="formatMoney(product.price)"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Cart -->
        <div class="card p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-700 dark:text-navy-100">Savat</h3>
                <button @click="clearCart" type="button" class="btn h-8 rounded-full bg-error/10 px-3 text-xs font-medium text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                    <i class="fa-solid fa-trash mr-1"></i> Tozalash
                </button>
            </div>

            <div x-show="cart.length === 0" class="text-center py-8">
                <i class="fa-solid fa-cart-shopping text-4xl text-slate-300 dark:text-navy-400"></i>
                <p class="mt-2 text-slate-400 dark:text-navy-300">Savat bo'sh</p>
            </div>

            <div x-show="cart.length > 0" class="space-y-2">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-navy-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <p class="font-medium text-sm" x-text="item.product.name"></p>
                                <p class="text-xs text-slate-400" x-text="formatMoney(item.price) + ' / ' + item.product.unit"></p>
                            </div>
                            <button @click="removeFromCart(index)" type="button" class="btn size-7 rounded-full bg-error/10 p-0 font-medium text-error hover:bg-error/20">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <!-- Quantity Controls -->
                        <div class="flex items-center space-x-2">
                            <button @click="decreaseQuantity(index)" type="button" class="btn size-8 rounded-lg bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            
                            <!-- Large Quantity Input -->
                            <input x-model.number="item.quantity" @change="updateCart" type="number" step="0.01" min="0.01" class="form-input flex-1 rounded-lg border-2 border-primary/30 bg-white dark:bg-navy-700 px-3 py-2 text-center text-lg font-semibold focus:border-primary">
                            
                            <button @click="increaseQuantity(index)" type="button" class="btn size-8 rounded-lg bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        
                        <!-- Quick Add Buttons -->
                        <div class="flex items-center space-x-1 mt-2">
                            <span class="text-xs text-slate-500 mr-1">Tez qo'shish:</span>
                            <button @click="addQuantity(index, 10)" type="button" class="btn h-6 rounded bg-info/10 px-2 text-xs font-medium text-info hover:bg-info/20">
                                +10
                            </button>
                            <button @click="addQuantity(index, 50)" type="button" class="btn h-6 rounded bg-info/10 px-2 text-xs font-medium text-info hover:bg-info/20">
                                +50
                            </button>
                            <button @click="addQuantity(index, 100)" type="button" class="btn h-6 rounded bg-info/10 px-2 text-xs font-medium text-info hover:bg-info/20">
                                +100
                            </button>
                        </div>
                        
                        <!-- Subtotal -->
                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-slate-200 dark:border-navy-500">
                            <span class="text-xs text-slate-500">Jami:</span>
                            <p class="font-bold text-primary" x-text="formatMoney(item.subtotal)"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Right Side: Payment & Summary -->
    <div class="space-y-4">
        <!-- Summary -->
        <div class="card p-4">
            <h3 class="text-lg font-semibold text-slate-700 dark:text-navy-100 mb-4">Jami</h3>
            
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600 dark:text-navy-300">Oraliq jami:</span>
                    <span class="font-medium" x-text="formatMoney(subtotal)"></span>
                </div>
                
                <!-- Discount -->
                <div class="border-t border-slate-200 dark:border-navy-500 pt-2">
                    <label class="block mb-2">
                        <span class="text-slate-600 dark:text-navy-300">Chegirma:</span>
                        <div class="flex space-x-2 mt-1">
                            <input x-model.number="discountPercent" @input="calculateTotals" type="number" min="0" max="100" placeholder="%" class="form-input w-20 rounded-lg border border-slate-300 bg-transparent px-2 py-1 text-sm">
                            <input x-model.number="discountAmount" @input="calculateTotals" type="number" min="0" placeholder="Summa" class="form-input flex-1 rounded-lg border border-slate-300 bg-transparent px-2 py-1 text-sm">
                        </div>
                    </label>
                    <div class="flex justify-between text-error">
                        <span>Chegirma:</span>
                        <span class="font-medium" x-text="'-' + formatMoney(discount)"></span>
                    </div>
                </div>

                <div class="border-t border-slate-200 dark:border-navy-500 pt-2 flex justify-between text-lg font-bold text-primary">
                    <span>JAMI:</span>
                    <span x-text="formatMoney(total)"></span>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="card p-4">
            <h3 class="text-lg font-semibold text-slate-700 dark:text-navy-100 mb-4">To'lov turi</h3>
            
            <div class="grid grid-cols-2 gap-2 mb-4">
                <button @click="paymentType = 'cash'; calculateChange()" type="button" :class="paymentType === 'cash' ? 'bg-success text-white' : 'bg-slate-150 text-slate-800 dark:bg-navy-500 dark:text-navy-50'" class="btn h-10 rounded-lg font-medium">
                    <i class="fa-solid fa-money-bill mr-1"></i> Naqd
                </button>
                <button @click="paymentType = 'card'; calculateChange()" type="button" :class="paymentType === 'card' ? 'bg-info text-white' : 'bg-slate-150 text-slate-800 dark:bg-navy-500 dark:text-navy-50'" class="btn h-10 rounded-lg font-medium">
                    <i class="fa-solid fa-credit-card mr-1"></i> Plastik
                </button>
                <button @click="paymentType = 'debt'; calculateChange()" type="button" :class="paymentType === 'debt' ? 'bg-warning text-white' : 'bg-slate-150 text-slate-800 dark:bg-navy-500 dark:text-navy-50'" class="btn h-10 rounded-lg font-medium">
                    <i class="fa-solid fa-clock mr-1"></i> Qarz
                </button>
                <button @click="paymentType = 'mixed'; calculateChange()" type="button" :class="paymentType === 'mixed' ? 'bg-secondary text-white' : 'bg-slate-150 text-slate-800 dark:bg-navy-500 dark:text-navy-50'" class="btn h-10 rounded-lg font-medium">
                    <i class="fa-solid fa-shuffle mr-1"></i> Aralash
                </button>
            </div>

            <!-- Cash Payment -->
            <div x-show="paymentType === 'cash' || paymentType === 'mixed'" class="space-y-2">
                <label class="block">
                    <span class="text-sm text-slate-600 dark:text-navy-300">Naqd to'lov:</span>
                    <input x-model.number="paidCash" @input="calculateChange" type="number" min="0" step="0.01" class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2">
                </label>
                <div x-show="paymentType === 'cash' && change > 0" class="flex justify-between text-sm">
                    <span class="text-slate-600 dark:text-navy-300">Qaytim:</span>
                    <span class="font-medium text-success" x-text="formatMoney(change)"></span>
                </div>
            </div>

            <!-- Card Payment -->
            <div x-show="paymentType === 'card' || paymentType === 'mixed'" class="mt-2">
                <label class="block">
                    <span class="text-sm text-slate-600 dark:text-navy-300">Plastik to'lov:</span>
                    <input x-model.number="paidCard" @input="calculateChange" type="number" min="0" step="0.01" class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2">
                </label>
            </div>

            <!-- Debt Info for Mixed/Debt -->
            <div x-show="debt > 0" class="mt-2 p-3 rounded-lg bg-warning/10 border border-warning/20">
                <div class="flex justify-between text-warning font-medium">
                    <span>Qarzga yoziladi:</span>
                    <span x-text="formatMoney(debt)"></span>
                </div>
                <p x-show="!customerId" class="text-xs text-error mt-1">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Qarz yozish uchun mijozni tanlang!
                </p>
            </div>

            <!-- Debt Warning -->
            <div x-show="paymentType === 'debt' && !customerId" class="mt-2 p-2 rounded-lg bg-error/10 text-error text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Qarz uchun mijoz tanlang
            </div>
        </div>

        <!-- Notes -->
        <div class="card p-4">
            <label class="block">
                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Izoh (ixtiyoriy)</span>
                <textarea x-model="notes" rows="2" class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
            </label>
        </div>

        <!-- Complete Sale Button -->
        <button @click="completeSale" :disabled="cart.length === 0" type="button" class="btn w-full h-12 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fa-solid fa-check-circle mr-2"></i> Sotuvni yakunlash
        </button>
    </div>
</div>

<form x-ref="saleForm" method="POST" action="{{ route('sales.store') }}" style="display: none;">
    @csrf
    <input type="hidden" name="customer_id" x-model="customerId">
    <input type="hidden" name="new_customer_name" x-model="newCustomerName">
    <input type="hidden" name="new_customer_phone" x-model="newCustomerPhone">
    <input type="hidden" name="discount" x-model="discountAmount">
    <input type="hidden" name="discount_percent" x-model="discountPercent">
    <input type="hidden" name="payment_type" x-model="paymentType">
    <input type="hidden" name="paid_cash" x-model="paidCash">
    <input type="hidden" name="paid_card" x-model="paidCard">
    <input type="hidden" name="notes" x-model="notes">
    <template x-for="(item, index) in cart" :key="index">
        <div>
            <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product.id">
            <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">
            <input type="hidden" :name="'items[' + index + '][price]'" :value="item.price">
        </div>
    </template>
</form>

@push('scripts')
<script>
function posSystem() {
    return {
        products: @json($products),
        filteredProducts: @json($products),
        searchQuery: '',
        cart: @json($cart ?? []),
        customerId: '{{ $customerId ?? "" }}',
        newCustomerName: '{{ $prefillCustomer["name"] ?? "" }}',
        newCustomerPhone: '{{ $prefillCustomer["phone"] ?? "" }}',
        telegramOrderId: '{{ $telegramOrderId ?? "" }}',
        subtotal: 0,
        discountPercent: 0,
        discountAmount: 0,
        discount: 0,
        total: 0,
        paymentType: 'cash',
        paidCash: 0,
        paidCard: 0,
        change: 0,
        debt: 0,
        notes: '',

        filterProducts() {
            if (this.searchQuery === '') {
                this.filteredProducts = this.products;
            } else {
                const query = this.searchQuery.toLowerCase();
                this.filteredProducts = this.products.filter(product => 
                    product.name.toLowerCase().includes(query) || 
                    product.code.toLowerCase().includes(query)
                );
            }
        },

        addToCart(product) {
            const existingItem = this.cart.find(item => item.product.id === product.id);
            if (existingItem) {
                existingItem.quantity = parseFloat((existingItem.quantity + 1).toFixed(2));
            } else {
                this.cart.push({
                    product: product,
                    quantity: 1,
                    price: product.price,
                    subtotal: product.price
                });
            }
            this.updateCart();
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
            this.updateCart();
        },

        increaseQuantity(index) {
            this.cart[index].quantity = parseFloat((this.cart[index].quantity + 1).toFixed(2));
            this.updateCart();
        },

        decreaseQuantity(index) {
            if (this.cart[index].quantity > 0.01) {
                this.cart[index].quantity = parseFloat((this.cart[index].quantity - 1).toFixed(2));
                if (this.cart[index].quantity < 0.01) {
                    this.removeFromCart(index);
                } else {
                    this.updateCart();
                }
            }
        },

        addQuantity(index, amount) {
            this.cart[index].quantity = parseFloat((this.cart[index].quantity + amount).toFixed(2));
            this.updateCart();
        },

        updateCart() {
            this.cart.forEach(item => {
                item.subtotal = item.quantity * item.price;
            });
            this.calculateTotals();
        },

        calculateTotals() {
            this.subtotal = this.cart.reduce((sum, item) => sum + item.subtotal, 0);
            
            if (this.discountPercent > 0) {
                this.discount = this.subtotal * (this.discountPercent / 100);
                this.discountAmount = this.discount;
            } else {
                this.discount = this.discountAmount || 0;
            }
            
            this.total = this.subtotal - this.discount;
            this.calculateChange();
        },

        calculateChange() {
            this.change = 0;
            this.debt = 0;

            if (this.paymentType === 'cash') {
                if (this.paidCash > this.total) {
                    this.change = this.paidCash - this.total;
                }
            } else if (this.paymentType === 'mixed') {
                const totalPaid = (parseFloat(this.paidCash) || 0) + (parseFloat(this.paidCard) || 0);
                if (totalPaid < this.total) {
                    this.debt = this.total - totalPaid;
                } else {
                    this.change = totalPaid - this.total;
                }
            } else if (this.paymentType === 'debt') {
                this.debt = this.total;
            }
        },

        clearCart() {
            if (window.confirm('Savatni tozalashni xohlaysizmi?')) {
                this.cart = [];
                this.updateCart();
            }
        },

        async completeSale() {
            if (this.cart.length === 0) {
                this.showNotification('Savatga mahsulot qo\'shing', 'warning');
                return;
            }

            // Check if debt payment is selected but no customer
            if ((this.paymentType === 'debt' || this.debt > 0) && !this.customerId && !this.newCustomerName) {
                this.showNotification('Qarz yozish uchun mijozni tanlang (yoki yangi mijoz ma\'lumotlari kerak)!', 'warning');
                return;
            }

            try {
                const response = await fetch("{{ route('sales.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        customer_id: this.customerId,
                        items: this.cart.map(item => ({
                            product_id: item.product.id,
                            quantity: item.quantity,
                            price: item.price
                        })),
                        discount: this.discountAmount,
                        discount_percent: this.discountPercent,
                        payment_type: this.paymentType,
                        paid_cash: this.paidCash,
                        paid_card: this.paidCard,
                        notes: this.notes,
                        telegram_order_id: this.telegramOrderId
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = data.redirect;
                } else {
                    let message = data.message || 'Xatolik yuz berdi';
                    if (data.errors) {
                        message = Object.values(data.errors).flat().join('\n');
                    }
                    this.showNotification(message, 'error');
                }
            } catch (error) {
                console.error('Sale error:', error);
                this.showNotification('Tizim xatoligi yuz berdi. Internetni tekshiring.', 'error');
            }
        },

        showNotification(text, variant = 'info') {
            window.dispatchEvent(new CustomEvent('notify', { detail: { text: text, variant: variant } }));
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('uz-UZ').format(amount) + ' so\'m';
        }
    }
}
</script>
@endpush
@endsection
