<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->string('coato_code', 16)->nullable();
            $table->unsignedInteger('tax_id')->nullable();
            $table->string('name_uz');
            $table->string('name_oz');
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->timestamps();

            $table->index('region_id');
            $table->index('coato_code');
            $table->unique(['region_id', 'name_uz']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};