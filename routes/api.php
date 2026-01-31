<?php

use App\Http\Controllers\TelegramOrderController;
use Illuminate\Support\Facades\Route;

// Telegram Webhook (No authentication required)
Route::post('/telegram/webhook', [TelegramOrderController::class, 'webhook'])->name('telegram.webhook');
