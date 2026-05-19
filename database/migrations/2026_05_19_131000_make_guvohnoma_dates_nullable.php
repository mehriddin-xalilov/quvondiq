<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guvohnomalar', function (Blueprint $table) {
            $table->date('berilgan_sanasi')->nullable()->change();
            $table->string('protokol_raqami')->nullable()->change();
            $table->date('protokol_sanasi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('guvohnomalar', function (Blueprint $table) {
            $table->date('berilgan_sanasi')->nullable(false)->change();
            $table->string('protokol_raqami')->nullable(false)->change();
            $table->date('protokol_sanasi')->nullable(false)->change();
        });
    }
};
