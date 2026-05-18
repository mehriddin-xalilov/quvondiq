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

    /**
     * Qr-kodni skanerlaganda yuklab olinadigan fayl.
     * LibreOffice bo'lsa PDF, aks holda asl DOCX.
     */
    public function downloadPdf(string $code)
    {
        $guvohnoma = Guvohnoma::where('verify_code', $code)->firstOrFail();

        if (!$guvohnoma->guvohnoma_path) {
            return back()->with('error', 'Fayl topilmadi.');
        }

        $docxAbsolute = Storage::disk('local')->path($guvohnoma->guvohnoma_path);
        if (!file_exists($docxAbsolute)) {
            return back()->with('error', 'Fayl topilmadi.');
        }

        $pdfPath = $this->generator->convertDocxToPdf($docxAbsolute);
        if ($pdfPath !== null) {
            return response()->download(
                $pdfPath,
                sprintf('Guvohnoma_%s.pdf', $guvohnoma->raqam)
            );
        }

        return response()->download(
            $docxAbsolute,
            sprintf('Guvohnoma_%s.docx', $guvohnoma->raqam)
        );
    }
}