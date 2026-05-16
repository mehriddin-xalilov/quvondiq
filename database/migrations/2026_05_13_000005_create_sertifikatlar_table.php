<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sertifikatlar', function (Blueprint $table) {
            $table->id();

            // Sertifikat raqami (unique)
            $table->string('seria', 8)->nullable();              // QV
            $table->string('raqam')->unique();                   // 012880

            // Xodim F.I.O. — 3 tilda (UZ-lotin, EN, RU)
            $table->string('familiya_uz');
            $table->string('familiya_en')->nullable();
            $table->string('familiya_ru')->nullable();

            $table->string('ism_uz');
            $table->string('ism_en')->nullable();
            $table->string('ism_ru')->nullable();

            $table->string('otasi_ismi_uz')->nullable();
            $table->string('otasi_ismi_en')->nullable();
            $table->string('otasi_ismi_ru')->nullable();

            // Joylashuv — markaz qaysi tumanda
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();

            // Kasb — 3 tilda
            $table->foreignId('profession_id')->nullable()->constrained('professions')->nullOnDelete();
            $table->string('kasb_uz');
            $table->string('kasb_en')->nullable();
            $table->string('kasb_ru')->nullable();

            // O'qish davomiyligi
            $table->date('boshlanish_sanasi');
            $table->date('tugash_sanasi');
            $table->unsignedInteger('soat');                     // 360

            // Direktor F.I.O. (3 tilda emas, faqat asl yozuvi)
            $table->string('direktor_fio');

            // Ro'yxatga olish
            $table->string('registratsiya_raqami')->nullable();
            $table->date('registratsiya_sanasi')->nullable();

            // Generatsiya qilingan fayl
            $table->string('certificate_path')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikatlar');
    }
};