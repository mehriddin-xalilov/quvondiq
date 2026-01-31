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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('unit', ['kg', 'dona', 'qop', 'litr'])->default('kg');
            $table->decimal('price', 12, 2);
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->decimal('min_stock_level', 10, 2)->default(50);
            $table->decimal('optimal_stock_level', 10, 2)->default(500);
            $table->boolean('is_active')->default(true);
            $table->integer('sales_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category_id', 'is_active']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
