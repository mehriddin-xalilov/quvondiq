<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guvohnomalar', function (Blueprint $table) {
            $table->string('verify_code', 16)->unique()->nullable()->after('raqam');
        });
    }

    public function down(): void
    {
        Schema::table('guvohnomalar', function (Blueprint $table) {
            $table->dropColumn('verify_code');
        });
    }
};
