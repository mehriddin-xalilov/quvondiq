<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use App\Services\DocumentGenerationService;
use Illuminate\Support\Facades\Storage;

class SertifikatVerifyController extends Controller
{
    public function __construct(private readonly DocumentGenerationService $generator)
    {
    }

    public function show(string $code)
    {
        $sertifikat = Sertifikat::where('verify_code', $code)
            ->with(['profession', 'region', 'district'])
            ->firstOrFail();

        return view('verify.sertifikat', compact('sertifikat'));
    }

    public function downloadPdf(string $code)
    {
        $sertifikat = Sertifikat::where('verify_code', $code)->firstOrFail();

        if (!$sertifikat->certificate_path) {
            return back()->with('error', 'Fayl topilmadi.');
        }

        $this->ensurePdf($sertifikat);

        $abs = Storage::disk('local')->path($sertifikat->certificate_path);
        if (!file_exists($abs)) {
            return back()->with('error', 'Fayl topilmadi.');
        }

        $ext = pathinfo($abs, PATHINFO_EXTENSION) ?: 'pdf';

        return response()->download(
            $abs,
            sprintf('Sertifikat_%s.%s', $sertifikat->raqam, $ext)
        );
    }

    private function ensurePdf(Sertifikat $sertifikat): void
    {
        $path = $sertifikat->certificate_path;
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
        $sertifikat->certificate_path = ltrim(substr($pdfAbs, strlen($localRoot)), DIRECTORY_SEPARATOR . '/');
        $sertifikat->save();
    }
}
