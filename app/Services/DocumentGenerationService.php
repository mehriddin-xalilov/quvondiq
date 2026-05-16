<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class DocumentGenerationService
{
    /**
     * Shablon .docx'ni o'qib, `{{key}}` placeholder'larni $data dan keladigan qiymatlar
     * bilan almashtirib, yangi .docx ni `storage/app/$outputSubdir/...` ga yozadi.
     * Shrift va Word formatlash run'lar tegmaganligi sababli buzilmaydi.
     */
    public function generate(DocumentTemplate $template, array $data, string $outputSubdir): string
    {
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

        $this->replacePlaceholdersInDocx($fullOutputPath, $data);

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

        $xml = $zip->getFromName('word/document.xml') ?: '';
        $zip->close();

        $plain = strip_tags($xml);
        preg_match_all('/\{\{\s*([\w\-]+)\s*\}\}/u', $plain, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    private function replacePlaceholdersInDocx(string $docxPath, array $data): void
    {
        $zip = new ZipArchive();
        if ($zip->open($docxPath) !== true) {
            throw new RuntimeException('.docx faylini ochib bo\'lmadi.');
        }

        // Ko'pincha placeholder document.xml ichida bo'ladi, lekin header/footer'da
        // ham bo'lishi mumkin — barchasini ko'rib chiqamiz.
        $targets = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (preg_match('#^word/(document|header\d*|footer\d*)\.xml$#', $name)) {
                $targets[] = $name;
            }
        }

        foreach ($targets as $name) {
            $xml = $zip->getFromName($name);
            if ($xml === false) {
                continue;
            }
            $newXml = $this->replaceInXml($xml, $data);
            if ($newXml !== $xml) {
                $zip->deleteName($name);
                $zip->addFromString($name, $newXml);
            }
        }

        $zip->close();
    }

    private function replaceInXml(string $xml, array $data): string
    {
        return preg_replace_callback(
            '/\{\{\s*([\w\-]+)\s*\}\}/u',
            function (array $m) use ($data) {
                $key = $m[1];
                $value = $data[$key] ?? '';
                if ($value === null) {
                    $value = '';
                }
                return $this->escape((string) $value);
            },
            $xml
        );
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}