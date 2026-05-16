<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/professions.json');

        if (!file_exists($path)) {
            $this->command->warn("professions.json topilmadi — seeder o'tkazib yuborildi.");
            return;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data)) {
            $this->command->error('professions.json noto‘g‘ri formatda.');
            return;
        }

        foreach ($data as $row) {
            Profession::updateOrCreate(
                ['name_uz' => $row['name_uz']],
                [
                    'code'    => $row['code'] ?? null,
                    'name_oz' => $row['name_oz'] ?? null,
                    'name_ru' => $row['name_ru'] ?? null,
                    'name_en' => $row['name_en'] ?? null,
                ]
            );
        }

        $this->command->info('Professions: ' . Profession::count());
    }
}