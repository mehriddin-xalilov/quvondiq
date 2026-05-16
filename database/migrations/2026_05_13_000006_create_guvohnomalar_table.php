<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guvohnomalar', function (Blueprint $table) {
            $table->id();

            // Guvohnoma raqami (unique)
            $table->string('raqam')->unique();                   // 01489

            // Xodim F.I.O. — 2 tilda (UZ-kirill, RU)
            $table->string('familiya_oz');                       // Шодиев
            $table->string('familiya_ru')->nullable();

            $table->string('ism_oz');                            // Хусен
            $table->string('ism_ru')->nullable();

            $table->string('otasi_ismi_oz')->nullable();         // Бахронович
            $table->string('otasi_ismi_ru')->nullable();

            // Joylashuv (berilgan joy: Карши)
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('berilgan_joy_oz')->nullable();       // Қарши
            $table->string('berilgan_joy_ru')->nullable();       // Карши

            // Mutaxassislik — 2 tilda
            $table->foreignId('profession_id')->nullable()->constrained('professions')->nullOnDelete();
            $table->string('mutaxassislik_oz');                  // Монтажник по...
            $table->string('mutaxassislik_ru')->nullable();
            $table->string('razryad', 8)->nullable();            // 5

            // O'qish davomiyligi
            $table->date('boshlanish_sanasi');
            $table->date('tugash_sanasi');
            $table->date('berilgan_sanasi');

            // Protokol
            $table->string('protokol_raqami');                   // ПИ-98
            $table->date('protokol_sanasi');

            // Mansabdor shaxslar
            $table->string('komissiya_raisi_fio');               // Таймуродов К.М
            $table->string('komissiya_azosi_fio')->nullable();   // Жумаев М.Р
            $table->string('direktor_fio');                      // Шодиев Хусен Бахронович

            // Baholar
            $table->string('ball_umumiy_oz')->nullable();        // аъло
            $table->string('ball_umumiy_ru')->nullable();        // отлично
            $table->string('ball_maxsus_oz')->nullable();
            $table->string('ball_maxsus_ru')->nullable();
            $table->string('ball_ishlab_chiqarish_oz')->nullable();
            $table->string('ball_ishlab_chiqarish_ru')->nullable();

            // Generatsiya qilingan fayl
            $table->string('guvohnoma_path')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guvohnomalar');
    }
};