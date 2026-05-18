<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class DocumentGenerationService
{
    /**
     * Shablon .docx'ni ochib, `{{key}}` placeholder'larni $data qiymatlari bilan
     * almashtirib, generatsiya qilingan .docx'ni `storage/app/private/$outputSubdir/...`
     * ga yozadi. Agar $qrUrl berilgan bo'lsa, `{{code}}` placeholder o'rniga QR
     * kod rasmini joylaydi.
     */
    public function generate(
        DocumentTemplate $template,
        array $data,
        string $outputSubdir,
        ?string $qrUrl = null
    ): string {
        $templateFullPath = Storage::disk('local')->path($template->file_path);
        if (!file_exists($templateFullPath)) {
            throw new RuntimeException("Shablon fayli topilmadi: {$template->file_path}");
        }

        $filename = sprintf(
            '%s_%s.docx',
            Str::slug($template->slug ?: 'document'),
            now()->format('Ymd_His') . '_' . Str::random(6)
        );
        $relativePath = trim($outputSubdir, '/') . '/' . $filename;
        $fullOutputPath = Storage::disk('local')->path($relativePath);

        $dir = dirname($fullOutputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        copy($templateFullPath, $fullOutputPath);

        $qrPngPath = $qrUrl !== null ? $this->renderQrPng($qrUrl) : null;

        try {
            $this->processDocx($fullOutputPath, $data, $qrPngPath);
        } finally {
            if ($qrPngPath !== null && file_exists($qrPngPath)) {
                @unlink($qrPngPath);
            }
        }

        return $relativePath;
    }

    public function extractPlaceholders(string $absolutePath): array
    {
        if (!file_exists($absolutePath)) {
            throw new RuntimeException("Fayl topilmadi: {$absolutePath}");
        }

        $zip = new ZipArchive();
        if ($zip->open($absolutePath) !== true) {
            throw new RuntimeException('Fayl .docx formatida emas.');
        }

        $variables = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (!preg_match('#^word/(document|header\d*|footer\d*)\.xml$#', $name)) {
                continue;
            }
            $xml = $zip->getFromName($name);
            if ($xml === false) {
                continue;
            }
            $plain = preg_replace('/<[^>]+>/u', '', $xml);
            preg_match_all('/\{\{\s*([\w\-]+)\s*\}\}/u', $plain, $matches);
            $variables = array_merge($variables, $matches[1]);
        }
        $zip->close();

        return array_values(array_unique($variables));
    }

    /**
     * Berilgan .docx faylni yonidagi .pdf'ga konvertatsiya qiladi.
     * LibreOffice CLI (`soffice`) bo'lishi kerak. Aks holda null qaytaradi.
     */
    public function convertDocxToPdf(string $docxAbsolutePath): ?string
    {
        if (!file_exists($docxAbsolutePath)) {
            return null;
        }

        $outDir = dirname($docxAbsolutePath);
        $pdfPath = $outDir . '/' . pathinfo($docxAbsolutePath, PATHINFO_FILENAME) . '.pdf';

        if (file_exists($pdfPath) && filemtime($pdfPath) >= filemtime($docxAbsolutePath)) {
            return $pdfPath;
        }

        $binary = $this->findLibreOfficeBinary();
        if ($binary === null) {
            return null;
        }

        $cmd = sprintf(
            '%s --headless --norestore --nolockcheck --nodefault --nofirststartwizard --convert-to pdf --outdir %s %s 2>&1',
            escapeshellarg($binary),
            escapeshellarg($outDir),
            escapeshellarg($docxAbsolutePath)
        );

        $output = [];
        $exitCode = 0;
        exec($cmd, $output, $exitCode);

        return ($exitCode === 0 && file_exists($pdfPath)) ? $pdfPath : null;
    }

    private function findLibreOfficeBinary(): ?string
    {
        foreach (['soffice', 'libreoffice'] as $name) {
            $path = trim((string) @shell_exec('command -v ' . escapeshellarg($name) . ' 2>/dev/null'));
            if ($path !== '' && is_executable($path)) {
                return $path;
            }
        }
        foreach ([
            '/usr/bin/soffice',
            '/usr/bin/libreoffice',
            '/opt/libreoffice/program/soffice',
            '/Applications/LibreOffice.app/Contents/MacOS/soffice',
        ] as $path) {
            if (is_executable($path)) {
                return $path;
            }
        }
        return null;
    }

    private function processDocx(string $docxPath, array $data, ?string $qrPngPath): void
    {
        $zip = new ZipArchive();
        if ($zip->open($docxPath) !== true) {
            throw new RuntimeException('.docx faylini ochib bo\'lmadi.');
        }

        $parts = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (preg_match('#^word/(document|header\d*|footer\d*)\.xml$#', $name)) {
                $parts[] = $name;
            }
        }

        $imageRelIds = [];
        $imageMediaName = 'qr_image.png';
        $nextRelId = $this->nextFreeRelationshipId($zip, 'word/_rels/document.xml.rels');

        foreach ($parts as $partName) {
            $xml = $zip->getFromName($partName);
            if ($xml === false) {
                continue;
            }

            $xml = $this->normalizeMacros($xml);

            if ($qrPngPath !== null && str_contains($xml, '{{code}}')) {
                $rid = 'rId' . $nextRelId;
                $imageRelIds[$partName] = $rid;
                $nextRelId++;

                $xml = str_replace(
                    '{{code}}',
                    $this->buildImageXml($rid),
                    $xml
                );
            }

            $xml = $this->replaceMacros($xml, $data);

            $zip->deleteName($partName);
            $zip->addFromString($partName, $xml);
        }

        if (!empty($imageRelIds) && $qrPngPath !== null) {
            $imagePath = 'word/media/' . $imageMediaName;
            $zip->deleteName($imagePath);
            $zip->addFromString($imagePath, file_get_contents($qrPngPath));

            $this->ensurePngContentType($zip);

            foreach ($imageRelIds as $partName => $rid) {
                $relsName = $this->relsNameFor($partName);
                $relsXml = $zip->getFromName($relsName);
                $relsXml = $this->addImageRelationship(
                    $relsXml === false ? null : $relsXml,
                    $rid,
                    'media/' . $imageMediaName
                );
                $zip->deleteName($relsName);
                $zip->addFromString($relsName, $relsXml);
            }
        }

        $zip->close();
    }

    /**
     * `{{...}}` ko'rinishidagi macros'larni bir tekisga keltiradi — orasidagi
     * XML teglarni olib tashlaydi, oqibatda har bir macro bitta `<w:t>` ichida
     * toza ko'rinishda qoladi.
     */
    private function normalizeMacros(string $xml): string
    {
        return preg_replace_callback(
            '~\{(?:\s*<[^<>]+>\s*)*\{(?:[^{}]|<[^<>]+>)*?\}(?:\s*<[^<>]+>\s*)*\}~su',
            function (array $m): string {
                $plain = preg_replace('/<[^<>]+>/u', '', $m[0]);
                $plain = preg_replace('/\s+/u', '', $plain);
                if (preg_match('/^\{\{([a-zA-Z0-9_\-]+)\}\}$/', $plain, $kk)) {
                    return '{{' . $kk[1] . '}}';
                }
                return $m[0];
            },
            $xml
        ) ?? $xml;
    }

    private function replaceMacros(string $xml, array $data): string
    {
        return preg_replace_callback(
            '/\{\{\s*([a-zA-Z0-9_\-]+)\s*\}\}/u',
            function (array $m) use ($data): string {
                $key = $m[1];
                $value = $data[$key] ?? '';
                if ($value === null) {
                    $value = '';
                }
                if (is_bool($value)) {
                    $value = $value ? '1' : '';
                }
                return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
            },
            $xml
        );
    }

    /**
     * `{{code}}` o'rniga qo'yiladigan DrawingML rasm XML'ini quradi.
     * 3cm x 3cm (≈ 1080000 EMU) inline rasm.
     */
    private function buildImageXml(string $rid): string
    {
        $cx = 1080000;
        $cy = 1080000;
        $id = abs(crc32($rid)) % 1000000 + 100;

        return ''
            . '</w:t></w:r>'
            . '<w:r>'
            .   '<w:rPr><w:noProof/></w:rPr>'
            .   '<w:drawing>'
            .     '<wp:inline distT="0" distB="0" distL="0" distR="0" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing">'
            .       '<wp:extent cx="' . $cx . '" cy="' . $cy . '"/>'
            .       '<wp:effectExtent l="0" t="0" r="0" b="0"/>'
            .       '<wp:docPr id="' . $id . '" name="QR Code"/>'
            .       '<wp:cNvGraphicFramePr><a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/></wp:cNvGraphicFramePr>'
            .       '<a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">'
            .         '<a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">'
            .           '<pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">'
            .             '<pic:nvPicPr>'
            .               '<pic:cNvPr id="' . $id . '" name="QR Code"/>'
            .               '<pic:cNvPicPr><a:picLocks noChangeAspect="1" noChangeArrowheads="1"/></pic:cNvPicPr>'
            .             '</pic:nvPicPr>'
            .             '<pic:blipFill>'
            .               '<a:blip r:embed="' . $rid . '" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>'
            .               '<a:srcRect/>'
            .               '<a:stretch><a:fillRect/></a:stretch>'
            .             '</pic:blipFill>'
            .             '<pic:spPr bwMode="auto">'
            .               '<a:xfrm><a:off x="0" y="0"/><a:ext cx="' . $cx . '" cy="' . $cy . '"/></a:xfrm>'
            .               '<a:prstGeom prst="rect"><a:avLst/></a:prstGeom>'
            .             '</pic:spPr>'
            .           '</pic:pic>'
            .         '</a:graphicData>'
            .       '</a:graphic>'
            .     '</wp:inline>'
            .   '</w:drawing>'
            . '</w:r>'
            . '<w:r><w:t>';
    }

    private function relsNameFor(string $partName): string
    {
        return 'word/_rels/' . basename($partName) . '.rels';
    }

    private function nextFreeRelationshipId(ZipArchive $zip, string $relsFileName): int
    {
        $xml = $zip->getFromName($relsFileName);
        if ($xml === false || $xml === '') {
            return 1;
        }
        preg_match_all('/Id="rId(\d+)"/', $xml, $m);
        $max = 0;
        foreach ($m[1] ?? [] as $n) {
            $max = max($max, (int) $n);
        }
        return $max + 1;
    }

    private function addImageRelationship(?string $relsXml, string $rid, string $target): string
    {
        $relTag = '<Relationship Id="' . $rid . '"'
               . ' Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image"'
               . ' Target="' . $target . '"/>';

        if ($relsXml === null) {
            return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . $relTag
                . '</Relationships>';
        }
        return preg_replace('#</Relationships>\s*$#', $relTag . '</Relationships>', $relsXml);
    }

    private function ensurePngContentType(ZipArchive $zip): void
    {
        $name = '[Content_Types].xml';
        $xml = $zip->getFromName($name);
        if ($xml === false) {
            return;
        }
        if (str_contains($xml, 'Extension="png"')) {
            return;
        }
        $insert = '<Default Extension="png" ContentType="image/png"/>';
        $xml = preg_replace('#<Types[^>]*>#', '$0' . $insert, $xml, 1);
        $zip->deleteName($name);
        $zip->addFromString($name, $xml);
    }

    /**
     * QR kodni GD orqali PNG faylga chizadi (Imagick talab qilmaydi).
     */
    private function renderQrPng(string $content, int $cellSize = 12, int $marginCells = 2): string
    {
        if (!function_exists('imagecreatetruecolor')) {
            throw new RuntimeException('PHP GD kengaytmasi yo\'q — QR kod chiza olmadi.');
        }

        $qr = Encoder::encode($content, ErrorCorrectionLevel::M());
        $matrix = $qr->getMatrix();
        $size = $matrix->getWidth();

        $margin = $cellSize * $marginCells;
        $imageSize = $size * $cellSize + $margin * 2;

        $img = imagecreatetruecolor($imageSize, $imageSize);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefilledrectangle($img, 0, 0, $imageSize, $imageSize, $white);

        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    imagefilledrectangle(
                        $img,
                        $margin + $x * $cellSize,
                        $margin + $y * $cellSize,
                        $margin + ($x + 1) * $cellSize - 1,
                        $margin + ($y + 1) * $cellSize - 1,
                        $black
                    );
                }
            }
        }

        $tmp = tempnam(sys_get_temp_dir(), 'qr_');
        $path = $tmp . '.png';
        @unlink($tmp);
        imagepng($img, $path, 6);
        imagedestroy($img);

        return $path;
    }
}