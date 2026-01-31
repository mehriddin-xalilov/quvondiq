<?php

use App\Http\Controllers\TelegramOrderController;
use Illuminate\Support\Facades\Route;

// Telegram Webhook (No authentication, no CSRF, no session)
Route::post('/telegram/webhook', [TelegramOrderController::class, 'webhook'])
    ->name('telegram.webhook')
    ->withoutMiddleware(['web', 'csrf']);
