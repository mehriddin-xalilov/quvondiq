<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('coato_code', 16)->nullable();
            $table->unsignedInteger('tax_id')->nullable();
            $table->string('name_uz')->unique();
            $table->string('name_oz');
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->timestamps();

            $table->index('coato_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};