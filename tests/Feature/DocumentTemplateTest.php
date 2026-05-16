<?php

namespace Tests\Feature;

use App\Models\DocumentTemplate;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTemplateTest extends TestCase
{
    public function test_operator_cannot_create_templates(): void
    {
        $this->actingAsOperator();
        $this->get(route('templates.create'))->assertForbidden();
    }

    public function test_admin_can_view_templates_index(): void
    {
        $this->actingAsAdmin();
        $this->get(route('templates.index'))->assertOk();
    }

    public function test_admin_can_upload_a_docx_template(): void
    {
        Storage::fake('local');
        $this->actingAsAdmin();

        $file = UploadedFile::fake()->createWithContent(
            'sertifikat.docx',
            $this->makeFakeDocx('{{fio_uz}} {{raqam}}')
        );

        $response = $this->post(route('templates.store'), [
            'name'        => 'Sertifikat shabloni',
            'type'        => 'certificate',
            'description' => 'Test sertifikat',
            'file'        => $file,
            'is_active'   => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('document_templates', [
            'name' => 'Sertifikat shabloni',
            'type' => 'certificate',
            'slug' => 'sertifikat-shabloni',
        ]);
    }

    public function test_upload_requires_docx_file(): void
    {
        $this->actingAsAdmin();

        $file = UploadedFile::fake()->create('not-docx.pdf', 10);

        $this->post(route('templates.store'), [
            'name' => 'Wrong',
            'type' => 'certificate',
            'file' => $file,
        ])->assertSessionHasErrors('file');
    }

    public function test_show_page_extracts_placeholders(): void
    {
        Storage::fake('local');
        $this->actingAsAdmin();

        $tpl = $this->seedTemplateWithContent('{{fio_uz}} - {{raqam}}', 'certificate');

        $this->get(route('templates.show', $tpl))
            ->assertOk()
            ->assertSee('{{fio_uz}}')
            ->assertSee('{{raqam}}');
    }

    public function test_admin_can_delete_template(): void
    {
        Storage::fake('local');
        $this->actingAsAdmin();

        $tpl = $this->seedTemplateWithContent('{{x}}', 'guvohnoma');

        $this->delete(route('templates.destroy', $tpl))
            ->assertRedirect(route('templates.index'));

        $this->assertSoftDeleted('document_templates', ['id' => $tpl->id]);
    }

    /** Yengil .docx ZIP yaratamiz - faqat document.xml ichida placeholder bilan */
    private function makeFakeDocx(string $bodyText): string
    {
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
</Types>';

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>';

        $document = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
<w:body><w:p><w:r><w:t>'.htmlspecialchars($bodyText, ENT_XML1).'</w:t></w:r></w:p></w:body>
</w:document>';

        $tmp = tempnam(sys_get_temp_dir(), 'fake_docx_').'.docx';
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

    private function seedTemplateWithContent(string $body, string $type): DocumentTemplate
    {
        $relPath = 'templates/test_'.uniqid().'.docx';
        Storage::disk('local')->put($relPath, $this->makeFakeDocx($body));

        return DocumentTemplate::create([
            'name'              => 'Seeded '.$type,
            'slug'              => 'seeded-'.$type.'-'.uniqid(),
            'type'              => $type,
            'file_path'         => $relPath,
            'original_filename' => basename($relPath),
            'is_active'         => true,
        ]);
    }
}