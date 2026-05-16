<?php

namespace Tests\Feature;

use App\Models\DocumentTemplate;
use App\Services\DocumentGenerationService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentGenerationServiceTest extends TestCase
{
    public function test_extract_placeholders_returns_unique_keys(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('{{a}} {{b}} {{a}} {{c-d}}');

        $svc = app(DocumentGenerationService::class);
        $vars = $svc->extractPlaceholders(Storage::disk('local')->path($tpl->file_path));

        sort($vars);
        $this->assertSame(['a', 'b', 'c-d'], $vars);
    }

    public function test_generate_replaces_placeholders_and_keeps_format(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('Hello {{name}}, your code is {{code}}');

        $svc = app(DocumentGenerationService::class);
        $relPath = $svc->generate($tpl, ['name' => 'Quvondiq', 'code' => 'QV-001'], 'generated/unit-test');

        $xml = $this->getDocxBodyXml($relPath);
        $this->assertStringContainsString('Hello Quvondiq, your code is QV-001', $xml);
        $this->assertStringNotContainsString('{{name}}', $xml);
    }

    public function test_generate_escapes_special_chars(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('Name: {{name}}');

        $svc = app(DocumentGenerationService::class);
        $relPath = $svc->generate($tpl, ['name' => 'Test & <Co> "X"'], 'generated/unit-test');

        $xml = $this->getDocxBodyXml($relPath);
        $this->assertStringContainsString('Test &amp; &lt;Co&gt;', $xml);
    }

    public function test_generate_replaces_missing_keys_with_empty_string(): void
    {
        Storage::fake('local');
        $tpl = $this->seedTemplate('{{present}} | {{missing}}');

        $svc = app(DocumentGenerationService::class);
        $relPath = $svc->generate($tpl, ['present' => 'X'], 'generated/unit-test');

        $xml = $this->getDocxBodyXml($relPath);
        $this->assertStringContainsString('X | ', $xml);
        $this->assertStringNotContainsString('{{missing}}', $xml);
    }

    private function seedTemplate(string $body): DocumentTemplate
    {
        $relPath = 'templates/svc_test_'.uniqid().'.docx';
        Storage::disk('local')->put($relPath, $this->makeFakeDocx($body));

        return DocumentTemplate::create([
            'name'              => 'Svc test',
            'slug'              => 'svc-test-'.uniqid(),
            'type'              => 'certificate',
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