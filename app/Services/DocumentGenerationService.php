<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use RuntimeException;
use ZipArchive;

class DocumentGenerationService
{
    /**
     * Shablon .docx'ni ochib, `{{key}}` placeholder'larni $data qiymatlari bilan
     * almashtirib, generatsiya qilingan .docx'ni `storage/app/private/$outputSubdir/...`
     * ga yozadi. Agar $qrUrl berilgan bo'lsa, `{{code}}` placeholder o'rniga QR
     * kod rasmini qo'yadi.
     *
     * Qaytaradi: local diskka nisbatan saqlangan fayl yo'li.
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

        $tp = new TemplateProcessor($templateFullPath);
        $tp->setMacroChars('{{', '}}');
        $available = $tp->getVariables();

        $qrTempPath = null;
        if ($qrUrl !== null && in_array('code', $available, true)) {
            $qrTempPath = $this->renderQrPng($qrUrl);
            $tp->setImageValue('code', [
                'path'   => $qrTempPath,
                'width'  => 110,
                'height' => 110,
                'ratio'  => true,
            ]);
            unset($data['code']);
        }

        foreach ($available as $key) {
            if ($key === 'code' && $qrTempPath !== null) {
                continue;
            }
            $tp->setValue($key, $this->stringify($data[$key] ?? ''));
        }

        $tp->saveAs($fullOutputPath);

        if ($qrTempPath !== null && file_exists($qrTempPath)) {
            @unlink($qrTempPath);
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
     *
     * @param string $docxAbsolutePath
     * @return string|null PDF fayl yo'li (yoki agar konvertatsiya iloji bo'lmasa null)
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
        foreach (['/usr/bin/soffice', '/usr/bin/libreoffice', '/opt/libreoffice/program/soffice'] as $path) {
            if (is_executable($path)) {
                return $path;
            }
        }
        return null;
    }

    private function stringify(mixed $value): string
    {
        if ($value === null) {
            return '';
        }
        if (is_bool($value)) {
            return $value ? '1' : '';
        }
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /**
     * QR kodni GD orqali PNG faylga chizadi (Imagick talab qilmaydi).
     * Qaytaradi: vaqtinchalik PNG fayl yo'li.
     */
    private function renderQrPng(string $content, int $cellSize = 12, int $marginCells = 2): string
    {
        if (!function_exists('imagecreate')) {
            throw new RuntimeException('PHP GD kengaytmasi yo\'q — QR kod chiza olmadi.');
        }

        $qr = Encoder::encode($content, ErrorCorrectionLevel::M());
        $matrix = $qr->getMatrix();
        $size = $matrix->getWidth();

        $margin = $cellSize * $marginCells;
        $imageSize = $size * $cellSize + $margin * 2;

        $img = imagecreate($imageSize, $imageSize);
        imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

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

        $path = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
        imagepng($img, $path);
        imagedestroy($img);

        return $path;
    }
}