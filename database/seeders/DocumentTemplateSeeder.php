<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name'              => 'Sertifikat — Kasbiy Ko\'nikmalar Markazi',
                'slug'              => 'sertifikat-kasbiy-konikmalar',
                'type'              => DocumentTemplate::TYPE_CERTIFICATE,
                'description'       => "KASBI tuman Kasbiy Ko'nikmalar Markazi sertifikati (UZ-lotin / EN / RU)",
                'file_path'         => 'templates/sertifikat_kasbiy_konikmalar.docx',
                'original_filename' => 'Sertifikat_KKM.docx',
                'is_active'         => true,
            ],
            [
                'name'              => 'Guvohnoma — Nazorat Sifat Ta\'lim',
                'slug'              => 'guvohnoma-nazorat-sifat-talim',
                'type'              => DocumentTemplate::TYPE_GUVOHNOMA,
                'description'       => "«Назорат сифат таълим» o'quv markazi guvohnomasi (UZ-kirill / RU)",
                'file_path'         => 'templates/guvohnoma_nazorat_sifat_talim.docx',
                'original_filename' => 'Guvohnoma_NST.docx',
                'is_active'         => true,
            ],
        ];

        foreach ($templates as $data) {
            if (!Storage::disk('local')->exists($data['file_path'])) {
                $this->command->warn("Fayl topilmadi: {$data['file_path']}");
                continue;
            }

            DocumentTemplate::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('Document templates: ' . DocumentTemplate::count());
    }
}