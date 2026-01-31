<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->unique();
            $table->string('telegram_id')->unique()->nullable();
            $table->string('telegram_username')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_regular')->default(false);
            $table->decimal('total_debt', 15, 2)->default(0);
            $table->decimal('total_purchases', 15, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->timestamp('last_order_at')->nullable();
            $table->boolean('telegram_notifications')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['phone', 'is_regular']);
            $table->index('telegram_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
