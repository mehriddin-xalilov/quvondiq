<?php

namespace Tests\Feature;

use App\Models\DocumentTemplate;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SertifikatGenerationTest extends TestCase
{
    public function test_user_without_permission_cannot_create(): void
    {
        $user = User::create([
            'name' => 'Plain', 'email' => 'plain@test.uz',
            'password' => Hash::make('x'), 'is_active' => true,
        ]);
        $this->actingAs($user);
        $this->get(route('sertifikatlar.create'))->assertForbidden();
    }

    public function test_super_admin_can_view_create_form(): void
    {
        Storage::fake('local');
        $this->seedTemplate('certificate');
        $this->actingAsAdmin();

        $this->get(route('sertifikatlar.create'))
            ->assertOk()
            ->assertSee('Sertifikat raqami');
    }

    public function test_can_create_sertifikat_and_generate_docx(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('certificate', '{{seria}}{{raqam}} {{familiya_uz}} {{ism_uz}} {{kasb_uz}} {{boshlanish_sanasi}}');
        $this->actingAsAdmin();

        $payload = [
            'template_id'         => $tpl->id,
            'seria'               => 'QV',
            'raqam'               => '012880',
            'familiya_uz'         => 'TAYMURODOV',
            'ism_uz'              => 'QUVONDIQ',
            'otasi_ismi_uz'       => "MUROD O'G'LI",
            'kasb_uz'             => 'Elektrogazpayvandchi',
            'boshlanish_sanasi'   => '2026-02-10',
            'tugash_sanasi'       => '2026-05-05',
            'soat'                => 360,
            'direktor_fio'        => 'XONALIYEV UMIDJON BARNOYEVICH',
        ];

        $response = $this->post(route('sertifikatlar.store'), $payload);
        $response->assertRedirect();

        $this->assertDatabaseHas('sertifikatlar', [
            'raqam'       => '012880',
            'familiya_uz' => 'TAYMURODOV',
            'soat'        => 360,
        ]);

        $sert = Sertifikat::first();
        $this->assertNotNull($sert->certificate_path, 'certificate_path null bo\'lmasligi kerak');

        // Generatsiya qilingan .docx ichidan placeholder qiymatlarni tekshiramiz
        $generatedXml = $this->getDocxBodyXml($sert->certificate_path);
        $this->assertStringContainsString('TAYMURODOV', $generatedXml);
        $this->assertStringContainsString('Elektrogazpayvandchi', $generatedXml);
        $this->assertStringContainsString('10.02.2026', $generatedXml);
        $this->assertStringNotContainsString('{{seria}}', $generatedXml);
        $this->assertStringNotContainsString('{{kasb_uz}}', $generatedXml);
    }

    public function test_raqam_must_be_unique(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('certificate');
        $this->actingAsAdmin();

        Sertifikat::create([
            'raqam'             => '012880',
            'familiya_uz'       => 'A', 'ism_uz' => 'B',
            'kasb_uz'           => 'C',
            'boshlanish_sanasi' => '2026-01-01', 'tugash_sanasi' => '2026-02-01',
            'soat'              => 100, 'direktor_fio' => 'D',
        ]);

        $this->post(route('sertifikatlar.store'), [
            'template_id'       => $tpl->id,
            'raqam'             => '012880', // takror
            'familiya_uz'       => 'X', 'ism_uz' => 'Y', 'kasb_uz' => 'Z',
            'boshlanish_sanasi' => '2026-01-01', 'tugash_sanasi' => '2026-02-01',
            'soat'              => 200, 'direktor_fio' => 'W',
        ])->assertSessionHasErrors('raqam');
    }

    public function test_can_download_generated_docx(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('certificate', '{{raqam}}');
        $this->actingAsAdmin();

        $this->post(route('sertifikatlar.store'), [
            'template_id'       => $tpl->id,
            'raqam'             => '999999',
            'familiya_uz'       => 'A', 'ism_uz' => 'B', 'kasb_uz' => 'C',
            'boshlanish_sanasi' => '2026-01-01', 'tugash_sanasi' => '2026-02-01',
            'soat'              => 100, 'direktor_fio' => 'D',
        ]);

        $sert = Sertifikat::first();
        $response = $this->get(route('sertifikatlar.download', $sert));
        $response->assertOk();
        $response->assertHeader('content-disposition');
    }

    public function test_can_delete_sertifikat(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('certificate');
        $this->actingAsAdmin();

        $this->post(route('sertifikatlar.store'), [
            'template_id'       => $tpl->id,
            'raqam'             => '111111',
            'familiya_uz'       => 'A', 'ism_uz' => 'B', 'kasb_uz' => 'C',
            'boshlanish_sanasi' => '2026-01-01', 'tugash_sanasi' => '2026-02-01',
            'soat'              => 100, 'direktor_fio' => 'D',
        ]);

        $sert = Sertifikat::first();
        $this->delete(route('sertifikatlar.destroy', $sert))->assertRedirect();
        $this->assertSoftDeleted('sertifikatlar', ['id' => $sert->id]);
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