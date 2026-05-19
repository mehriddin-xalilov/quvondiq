<?php

namespace Tests\Feature;

use App\Models\DocumentTemplate;
use App\Models\Guvohnoma;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GuvohnomaGenerationTest extends TestCase
{
    public function test_guest_cannot_create(): void
    {
        $this->get(route('guvohnomalar.create'))->assertRedirect(route('login'));
    }

    public function test_can_create_guvohnoma_and_generate_docx(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('guvohnoma',
            '{{raqam}} {{familiya_oz}} {{ism_oz}} {{mutaxassislik_oz}} {{razryad}} {{protokol_raqami}}'
        );
        $this->actingAsAdmin();

        $payload = [
            'template_id'         => $tpl->id,
            'raqam'               => '01489',
            'familiya_ru'         => 'Шодиев',
            'ism_ru'              => 'Хусен',
            'otasi_ismi_ru'       => 'Бахронович',
            'mutaxassislik_oz'    => 'Пўлат конструкцияларни монтаж қилиш бўйича монтажчи',
            'razryad'             => '5',
            'boshlanish_sanasi'   => '2026-03-16',
            'tugash_sanasi'       => '2026-04-16',
            'berilgan_sanasi'     => '2026-04-16',
            'protokol_raqami'     => 'ПИ-98',
            'protokol_sanasi'     => '2026-04-16',
            'komissiya_raisi_fio' => 'Таймуродов К.М',
            'direktor_fio'        => 'Шодиев Хусен Бахронович',
        ];

        $this->post(route('guvohnomalar.store'), $payload)->assertRedirect();

        $this->assertDatabaseHas('guvohnomalar', [
            'raqam'       => '01489',
            'familiya_oz' => 'Шодиев',
            'razryad'     => '5',
        ]);

        $g = Guvohnoma::first();
        $this->assertNotNull($g->guvohnoma_path);

        $xml = $this->getDocxBodyXml($g->guvohnoma_path);
        $this->assertStringContainsString('Шодиев', $xml);
        $this->assertStringContainsString('ПИ-98', $xml);
        $this->assertStringContainsString('01489', $xml);
        $this->assertStringNotContainsString('{{raqam}}', $xml);
        $this->assertStringNotContainsString('{{razryad}}', $xml);
    }

    public function test_start_and_end_dates_required(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('guvohnoma');
        $this->actingAsAdmin();

        $this->post(route('guvohnomalar.store'), [
            'template_id'         => $tpl->id,
            'raqam'               => '00001',
            'familiya_ru'         => 'X', 'ism_ru' => 'Y',
            'mutaxassislik_oz'    => 'Z',
            // boshlanish/tugash sanalari yo'q
            'komissiya_raisi_fio' => 'A',
            'direktor_fio'        => 'B',
        ])->assertSessionHasErrors(['boshlanish_sanasi', 'tugash_sanasi']);
    }

    public function test_update_regenerates_docx(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('guvohnoma', '{{familiya_oz}}');
        $this->actingAsAdmin();

        $g = Guvohnoma::create([
            'raqam'               => '88888',
            'familiya_oz'         => 'Eski',
            'familiya_ru'         => 'Eski',
            'ism_oz'              => 'X',
            'ism_ru'              => 'X',
            'mutaxassislik_oz'    => 'Y',
            'boshlanish_sanasi'   => '2026-01-01',
            'tugash_sanasi'       => '2026-02-01',
            'berilgan_sanasi'     => '2026-02-01',
            'protokol_raqami'     => 'P-1',
            'protokol_sanasi'     => '2026-02-01',
            'komissiya_raisi_fio' => 'A',
            'direktor_fio'        => 'B',
        ]);

        $this->put(route('guvohnomalar.update', $g), [
            'template_id'         => $tpl->id,
            'raqam'               => '88888',
            'familiya_ru'         => 'Yangi',
            'ism_ru'              => 'X',
            'mutaxassislik_oz'    => 'Y',
            'boshlanish_sanasi'   => '2026-01-01',
            'tugash_sanasi'       => '2026-02-01',
            'berilgan_sanasi'     => '2026-02-01',
            'protokol_raqami'     => 'P-1',
            'protokol_sanasi'     => '2026-02-01',
            'komissiya_raisi_fio' => 'A',
            'direktor_fio'        => 'B',
        ])->assertRedirect();

        $g->refresh();
        $xml = $this->getDocxBodyXml($g->guvohnoma_path);
        $this->assertStringContainsString('Yangi', $xml);
        $this->assertStringNotContainsString('Eski', $xml);
    }

    private function seedTemplate(string $type, string $body = '{{raqam}}'): DocumentTemplate
    {
        $relPath = 'templates/'.$type.'_'.uniqid().'.docx';
        Storage::disk('local')->put($relPath, $this->makeFakeDocx($body));

        return DocumentTemplate::create([
            'name'              => $type.' shabloni',
            'slug'              => $type.'-'.uniqid(),
            'type'              => $type,
            'file_path'         => $relPath,
            'original_filename' => basename($relPath),
            'is_active'         => true,
        ]);
    }

    private function makeFakeDocx(string $bodyText): string
    {
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/></Types>';
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>';
        $document = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>'.htmlspecialchars($bodyText, ENT_XML1).'</w:t></w:r></w:p></w:body></w:document>';

        $tmp = tempnam(sys_get_temp_dir(), 'docx_').'.docx';
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::CREATE);
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rels);
        $zip->addFromString('word/document.xml', $document);
        $zip->close();
        $bytes = file_get_contents($tmp);
        unlink($tmp);
        return $bytes;
    }

    private function getDocxBodyXml(string $relativePath): string
    {
        $full = Storage::disk('local')->path($relativePath);
        $zip = new \ZipArchive();
        $zip->open($full);
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();
        return $xml;
    }
}