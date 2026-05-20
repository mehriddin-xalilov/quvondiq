<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guvohnomalar', function (Blueprint $table) {
            // Sohaning qisqartma nomi, masalan: "KM", "EG", "PQ"
            $table->string('speciality', 32)->nullable()->after('mutaxassislik_ru');
        });
    }

    public function down(): void
    {
        Schema::table('guvohnomalar', function (Blueprint $table) {
            $table->dropColumn('speciality');
        });
    }
};
