<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Avval fio_uz column qo'shamiz (nullable — eski qiymatlar uchun)
        Schema::table('sertifikatlar', function (Blueprint $table) {
            $table->string('fio_uz')->nullable()->after('raqam');
        });

        // 2. Mavjud yozuvlar uchun fio_uz ni to'ldiramiz
        DB::table('sertifikatlar')->whereNull('deleted_at')->lazyById()->each(function ($row) {
            $fio = trim("{$row->familiya_uz} {$row->ism_uz} " . ($row->otasi_ismi_uz ?? ''));
            DB::table('sertifikatlar')
                ->where('id', $row->id)
                ->update(['fio_uz' => $fio]);
        });

        // 3. fio_uz ni NOT NULL qilamiz
        Schema::table('sertifikatlar', function (Blueprint $table) {
            $table->string('fio_uz')->nullable(false)->change();
        });

        // 4. Eski 9 ta column ni o'chiramiz
        Schema::table('sertifikatlar', function (Blueprint $table) {
            $table->dropColumn([
                'familiya_uz', 'familiya_en', 'familiya_ru',
                'ism_uz',      'ism_en',      'ism_ru',
                'otasi_ismi_uz', 'otasi_ismi_en', 'otasi_ismi_ru',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('sertifikatlar', function (Blueprint $table) {
            $table->string('familiya_uz')->default('');
            $table->string('familiya_en')->nullable();
            $table->string('familiya_ru')->nullable();
            $table->string('ism_uz')->default('');
            $table->string('ism_en')->nullable();
            $table->string('ism_ru')->nullable();
            $table->string('otasi_ismi_uz')->nullable();
            $table->string('otasi_ismi_en')->nullable();
            $table->string('otasi_ismi_ru')->nullable();
            $table->dropColumn('fio_uz');
        });
    }
};
