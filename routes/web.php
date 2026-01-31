<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\WarehouseStockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TelegramOrderController;
use App\Http\Controllers\ShopInformationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Webhook Route (Exclude from CSRF)
Route::post('/api/telegram/webhook', [TelegramOrderController::class, 'webhook'])->name('telegram.webhook');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categories
    Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
    Route::resource('categories', CategoryController::class);

    // Products
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::resource('products', ProductController::class);

    // Warehouse Stocks
    Route::get('/warehouse-stocks', [WarehouseStockController::class, 'index'])->name('warehouse-stocks.index');

    // Stock Movements
    Route::prefix('stock-movements')->name('stock-movements.')->group(function () {
        Route::get('/export', [StockMovementController::class, 'export'])->name('export');
        Route::get('/', [StockMovementController::class, 'index'])->name('index');
        Route::get('/create', [StockMovementController::class, 'create'])->name('create');
        Route::post('/', [StockMovementController::class, 'store'])->name('store');
    });

    // Sales
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::get('/sales/create-from-telegram/{telegram_order_id}', [SaleController::class, 'createFromTelegram'])->name('sales.create_from_telegram');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/export', [SaleController::class, 'export'])->name('sales.export');
    Route::resource('sales', SaleController::class)->except(['edit', 'update', 'create', 'store']);

    // Customers
    // Customers
    Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::resource('customers', CustomerController::class);

    // Payments
    Route::resource('payments', PaymentController::class);

    // Expenses
    // Expenses
    Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::resource('expenses', ExpenseController::class);

    // Notes
    // Notes
    Route::get('/notes/export', [NoteController::class, 'export'])->name('notes.export');
    Route::resource('notes', NoteController::class);

    // Telegram Orders
    // Telegram Orders
    Route::get('/telegram-orders/export', [TelegramOrderController::class, 'export'])->name('telegram-orders.export');
    Route::resource('telegram-orders', TelegramOrderController::class)->only(['index', 'show', 'destroy']);
    Route::post('telegram-orders/simulate', [TelegramOrderController::class, 'simulate'])->name('telegram-orders.simulate');
    


    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        // Roles Management
        // Roles Management
        Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export');
        Route::resource('roles', RoleController::class)->middleware('permission:view-roles');
        
        // Users Management
        // Users Management
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::resource('users', UserController::class)->middleware('permission:view-users');

        // Shop Settings
        Route::get('/shop', [ShopInformationController::class, 'edit'])->name('shop.edit');
        Route::put('/shop', [ShopInformationController::class, 'update'])->name('shop.update');
    });
});

require __DIR__.'/auth.php';
