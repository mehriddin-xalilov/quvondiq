<?php

namespace App\Http\Controllers;

use App\Models\Guvohnoma;
use App\Services\DocumentGenerationService;
use Illuminate\Support\Facades\Storage;

class CertificateVerifyController extends Controller
{
    public function __construct(private readonly DocumentGenerationService $generator)
    {
    }

    public function show(string $code)
    {
        $guvohnoma = Guvohnoma::where('verify_code', $code)
            ->with(['profession', 'region', 'district'])
            ->firstOrFail();

        return view('verify.certificate', compact('guvohnoma'));
    }

    public function downloadPdf(string $code)
    {
        $guvohnoma = Guvohnoma::where('verify_code', $code)->firstOrFail();

        if (!$guvohnoma->guvohnoma_path) {
            return back()->with('error', 'Fayl topilmadi.');
        }

        $this->ensurePdf($guvohnoma);

        $abs = Storage::disk('local')->path($guvohnoma->guvohnoma_path);
        if (!file_exists($abs)) {
            return back()->with('error', 'Fayl topilmadi.');
        }

        $ext = pathinfo($abs, PATHINFO_EXTENSION) ?: 'pdf';

        return response()->download(
            $abs,
            sprintf('Guvohnoma_%s.%s', $guvohnoma->raqam, $ext)
        );
    }

    private function ensurePdf(Guvohnoma $guvohnoma): void
    {
        $path = $guvohnoma->guvohnoma_path;
        if (!$path || !str_ends_with(strtolower($path), '.docx')) {
            return;
        }

        $docxAbs = Storage::disk('local')->path($path);
        if (!file_exists($docxAbs)) {
            return;
        }

        $pdfAbs = $this->generator->convertDocxToPdf($docxAbs);
        if ($pdfAbs === null) {
            return;
        }

        @unlink($docxAbs);
        $localRoot = Storage::disk('local')->path('');
        $guvohnoma->guvohnoma_path = ltrim(substr($pdfAbs, strlen($localRoot)), DIRECTORY_SEPARATOR . '/');
        $guvohnoma->save();
    }
}