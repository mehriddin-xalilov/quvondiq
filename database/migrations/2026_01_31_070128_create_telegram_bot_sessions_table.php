<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_bot_sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('telegram_user_id')->index();
            $table->string('state')->default('idle'); // idle, browsing_categories, selecting_product, etc.
            $table->json('cart')->nullable(); // [{product_id, name, quantity, price, subtotal}]
            $table->json('context')->nullable(); // {selected_category_id, current_product_id, etc.}
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_bot_sessions');
    }
};
