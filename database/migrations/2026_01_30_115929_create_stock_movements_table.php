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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('quantity_before', 10, 2);
            $table->decimal('quantity_after', 10, 2);
            $table->decimal('price_per_unit', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('movement_date')->useCurrent();
            $table->timestamps();
            
            $table->index(['product_id', 'type', 'movement_date']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
