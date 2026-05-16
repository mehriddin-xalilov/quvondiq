<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    public function run(): void
    {
        $professions = [
            [
                'code'    => 'P-001',
                'name_uz' => 'Elektrogazpayvandchi',
                'name_oz' => 'Электрогазпайвандчи',
                'name_ru' => 'Электрогазосварщик',
                'name_en' => 'Electric gas welder',
            ],
            [
                'code'    => 'P-002',
                'name_uz' => 'Pulat va temir-beton konstruksiyalarni montaj qilish bo‘yicha montajchi',
                'name_oz' => 'Пўлат ва темир-бетон конструкцияларни монтаж қилиш бўйича монтажчи',
                'name_ru' => 'Монтажник по монтажу стальных и железобетонных конструкций',
                'name_en' => 'Installer of steel and reinforced concrete structures',
            ],
            [
                'code'    => 'P-003',
                'name_uz' => 'Texnologik kuvurlar montajchisi',
                'name_oz' => 'Технологик қувурлар монтажчиси',
                'name_ru' => 'Монтажник технологических трубопроводов',
                'name_en' => 'Process pipeline installer',
            ],
            [
                'code'    => 'P-004',
                'name_uz' => 'Betonchi',
                'name_oz' => 'Бетончи',
                'name_ru' => 'Бетонщик',
                'name_en' => 'Concrete worker',
            ],
            [
                'code'    => 'P-005',
                'name_uz' => 'Armaturachi',
                'name_oz' => 'Арматурачи',
                'name_ru' => 'Арматурщик',
                'name_en' => 'Reinforcement worker',
            ],
            [
                'code'    => 'P-006',
                'name_uz' => 'Kafelchi',
                'name_oz' => 'Кафелчи',
                'name_ru' => 'Облицовщик-плиточник',
                'name_en' => 'Tile setter',
            ],
            [
                'code'    => 'P-007',
                'name_uz' => 'Qoliplash ustasi (Opalubkachi)',
                'name_oz' => 'Қолиплаш устаси',
                'name_ru' => 'Опалубщик',
                'name_en' => 'Formwork carpenter',
            ],
            [
                'code'    => 'P-008',
                'name_uz' => 'Elektrik',
                'name_oz' => 'Электрик',
                'name_ru' => 'Электрик',
                'name_en' => 'Electrician',
            ],
            [
                'code'    => 'P-009',
                'name_uz' => 'Yordamchi ishchi',
                'name_oz' => 'Ёрдамчи ишчи',
                'name_ru' => 'Подсобный рабочий',
                'name_en' => 'General laborer',
            ],
            [
                'code'    => 'P-010',
                'name_uz' => 'Sanitariya-texnika ishlari ustasi (Suvchi)',
                'name_oz' => 'Санитария-техника устаси',
                'name_ru' => 'Сантехник',
                'name_en' => 'Plumber',
            ],
        ];

        foreach ($professions as $row) {
            Profession::updateOrCreate(
                ['name_uz' => $row['name_uz']],
                $row
            );
        }

        $this->command->info('Professions: ' . Profession::count());
    }
}