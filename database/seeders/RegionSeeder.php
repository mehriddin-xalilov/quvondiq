<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/regions.json');

        if (!file_exists($path)) {
            $this->command->warn("regions.json topilmadi: {$path}");
            return;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data)) {
            $this->command->error('regions.json noto‘g‘ri formatda.');
            return;
        }

        foreach ($data as $regionData) {
            $region = Region::updateOrCreate(
                ['name_uz' => $regionData['name_uz']],
                [
                    'coato_code' => $regionData['coato_code'] ?? null,
                    'tax_id'     => $regionData['tax_id'] ?? null,
                    'name_oz'    => $regionData['name_oz'] ?? $regionData['name_uz'],
                    'name_ru'    => $regionData['name_ru'] ?? null,
                    'name_en'    => $regionData['name_en'] ?? null,
                ]
            );

            foreach ($regionData['districts'] ?? [] as $districtData) {
                District::updateOrCreate(
                    [
                        'region_id' => $region->id,
                        'name_uz'   => $districtData['name_uz'],
                    ],
                    [
                        'coato_code' => $districtData['coato_code'] ?? null,
                        'tax_id'     => $districtData['tax_id'] ?? null,
                        'name_oz'    => $districtData['name_oz'] ?? $districtData['name_uz'],
                        'name_ru'    => $districtData['name_ru'] ?? null,
                        'name_en'    => $districtData['name_en'] ?? null,
                    ]
                );
            }
        }

        $this->command->info(sprintf(
            'Regions: %d, Districts: %d',
            Region::count(),
            District::count()
        ));
    }
}