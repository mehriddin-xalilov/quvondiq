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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->enum('source', ['offline', 'telegram'])->default('offline');
            
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('total', 15, 2);
            
            $table->enum('payment_type', ['cash', 'card', 'debt', 'mixed'])->default('cash');
            $table->decimal('paid_cash', 15, 2)->default(0);
            $table->decimal('paid_card', 15, 2)->default(0);
            $table->decimal('paid_total', 15, 2)->default(0);
            $table->decimal('debt_amount', 15, 2)->default(0);
            
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->text('notes')->nullable();
            $table->bigInteger('telegram_message_id')->nullable();
            
            $table->timestamp('sale_date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['sale_date', 'status']);
            $table->index(['customer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
