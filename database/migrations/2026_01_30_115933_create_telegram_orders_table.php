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
        Schema::create('telegram_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('telegram_user_id');
            $table->string('telegram_username')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();
            $table->json('items');
            $table->decimal('total', 15, 2);
            $table->enum('status', ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->bigInteger('telegram_message_id')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('telegram_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_orders');
    }
};
