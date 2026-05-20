<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sertifikatlar', function (Blueprint $table) {
            $table->string('verify_code', 16)->nullable()->unique()->after('raqam');
        });

        // Mavjud yozuvlar uchun verify_code generatsiya qilamiz
        \DB::table('sertifikatlar')->whereNull('deleted_at')->lazyById()->each(function ($row) {
            do {
                $code = strtoupper(Str::random(10));
            } while (\DB::table('sertifikatlar')->where('verify_code', $code)->exists());

            \DB::table('sertifikatlar')->where('id', $row->id)->update(['verify_code' => $code]);
        });

        // NOT NULL qilamiz
        Schema::table('sertifikatlar', function (Blueprint $table) {
            $table->string('verify_code', 16)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sertifikatlar', function (Blueprint $table) {
            $table->dropColumn('verify_code');
        });
    }
};
