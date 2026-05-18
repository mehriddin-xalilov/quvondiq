<?php

namespace App\Http\Controllers;

use App\Models\Guvohnoma;
use Illuminate\Support\Facades\Storage;

class CertificateVerifyController extends Controller
{
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
}