<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\DocumentTemplate;
use App\Models\Profession;
use App\Models\Region;
use App\Models\Sertifikat;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function __construct(private readonly DocumentGenerationService $generator)
    {
    }

    public function index()
    {
        $sertifikatlar = Sertifikat::with(['region', 'district', 'profession', 'creator'])
            ->orderByDesc('id')
            ->paginate(15);

        return view('sertifikatlar.index', compact('sertifikatlar'));
    }

    public function create()
    {
        return view('sertifikatlar.create', [
            'regions'     => Region::orderBy('name_uz')->get(),
            'districts'   => District::orderBy('name_uz')->get(),
            'professions' => Profession::orderBy('name_uz')->get(),
            'templates'   => DocumentTemplate::active()
                ->where('type', DocumentTemplate::TYPE_CERTIFICATE)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateInput($request);

        $template = DocumentTemplate::findOrFail($data['template_id']);
        unset($data['template_id']);

        $sertifikat = Sertifikat::create([
            ...$data,
            'created_by' => auth()->id(),
        ]);

        $sertifikat->certificate_path = $this->generateForSertifikat($template, $sertifikat);
        $sertifikat->save();

        return redirect()
            ->route('sertifikatlar.show', $sertifikat)
            ->with('success', 'Sertifikat yaratildi va tayyor.');
    }

    public function show(Sertifikat $sertifikat)
    {
        $sertifikat->load(['region', 'district', 'profession', 'creator']);
        return view('sertifikatlar.show', compact('sertifikat'));
    }

    public function edit(Sertifikat $sertifikat)
    {
        return view('sertifikatlar.edit', [
            'sertifikat'  => $sertifikat,
            'regions'     => Region::orderBy('name_uz')->get(),
            'districts'   => District::orderBy('name_uz')->get(),
            'professions' => Profession::orderBy('name_uz')->get(),
            'templates'   => DocumentTemplate::active()
                ->where('type', DocumentTemplate::TYPE_CERTIFICATE)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Sertifikat $sertifikat)
    {
        $data = $this->validateInput($request, $sertifikat->id);

        $template = DocumentTemplate::findOrFail($data['template_id']);
        unset($data['template_id']);

        if ($sertifikat->certificate_path) {
            Storage::disk('local')->delete($sertifikat->certificate_path);
        }

        $sertifikat->update($data);

        $sertifikat->certificate_path = $this->generateForSertifikat($template, $sertifikat);
        $sertifikat->save();

        return redirect()
            ->route('sertifikatlar.show', $sertifikat)
            ->with('success', 'Sertifikat yangilandi va qayta yaratildi.');
    }

    public function destroy(Sertifikat $sertifikat)
    {
        if ($sertifikat->certificate_path) {
            Storage::disk('local')->delete($sertifikat->certificate_path);
        }
        $sertifikat->delete();

        return redirect()
            ->route('sertifikatlar.index')
            ->with('success', 'Sertifikat o\'chirildi.');
    }

    public function download(Sertifikat $sertifikat)
    {
        abort_unless($sertifikat->certificate_path, 404);

        $abs = Storage::disk('local')->path($sertifikat->certificate_path);
        if (!file_exists($abs)) {
            return back()->with('error', 'Fayl topilmadi.');
        }

        $ext = pathinfo($abs, PATHINFO_EXTENSION) ?: 'pdf';

        return Storage::disk('local')->download(
            $sertifikat->certificate_path,
            sprintf('Sertifikat_%s.%s', $sertifikat->raqam, $ext)
        );
    }

    private function generateForSertifikat(DocumentTemplate $template, Sertifikat $sertifikat): string
    {
        $docxRel = $this->generator->generate(
            $template,
            $this->buildPlaceholderMap($sertifikat),
            'generated/sertifikatlar',
            $sertifikat->verifyUrl()   // ← QR code uchun URL
        );

        if (app()->environment('testing')) {
            return $docxRel;
        }

        $docxAbs = Storage::disk('local')->path($docxRel);
        $pdfAbs  = $this->generator->convertDocxToPdf($docxAbs);

        if ($pdfAbs === null) {
            return $docxRel;
        }

        @unlink($docxAbs);
        $localRoot = Storage::disk('local')->path('');
        return ltrim(substr($pdfAbs, strlen($localRoot)), DIRECTORY_SEPARATOR . '/');
    }

    private function validateInput(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'template_id'           => 'required|exists:document_templates,id',
            'seria'                 => 'nullable|string|max:8',
            'raqam'                 => 'required|string|max:64|unique:sertifikatlar,raqam' . ($ignoreId ? ',' . $ignoreId : ''),

            'fio_uz'                => 'required|string|max:500',

            'profession_id'         => 'nullable|exists:professions,id',
            'kasb_uz'               => 'required|string|max:255',
            'kasb_en'               => 'nullable|string|max:255',
            'kasb_ru'               => 'nullable|string|max:255',

            'boshlanish_sanasi'     => 'required|date',
            'tugash_sanasi'         => 'required|date|after_or_equal:boshlanish_sanasi',
            'soat'                  => 'required|integer|min:1|max:10000',

            'direktor_fio'          => 'required|string|max:255',
            'registratsiya_raqami'  => 'nullable|string|max:64',
            'registratsiya_sanasi'  => 'nullable|date',
        ]);
    }

    private function buildPlaceholderMap(Sertifikat $s): array
    {
        $region   = $s->region;
        $district = $s->district;
        $fio      = $s->fio_uz ?? '';
        $parts    = preg_split('/\s+/u', trim($fio));
        $fioUp    = mb_strtoupper($fio);
        $partsUp  = preg_split('/\s+/u', trim($fioUp));

        return [
            'seria'                 => $s->seria,
            'raqam'                 => $s->raqam,

            // Full FIO
            'fio_uz'                => $fio,
            'fio_en'                => $fioUp,
            'fio_ru'                => $fioUp,

            // Split FIO (lotin)
            'familiya_uz'           => $parts[0] ?? '',
            'ism_uz'                => $parts[1] ?? '',
            'otasi_ismi_uz'         => implode(' ', array_slice($parts, 2)),

            // Split FIO (katta harf — EN/RU)
            'familiya_en'           => $partsUp[0] ?? '',
            'ism_en'                => $partsUp[1] ?? '',
            'otasi_ismi_en'         => implode(' ', array_slice($partsUp, 2)),
            'familiya_ru'           => $partsUp[0] ?? '',
            'ism_ru'                => $partsUp[1] ?? '',
            'otasi_ismi_ru'         => implode(' ', array_slice($partsUp, 2)),

            'viloyat_uz'            => $region?->name_uz,
            'viloyat_ru'            => $region?->name_ru,
            'viloyat_en'            => $region?->name_en,
            'tuman_uz'              => $district?->name_uz,
            'tuman_ru'              => $district?->name_ru,
            'tuman_en'              => $district?->name_en,

            'kasb_uz'               => $s->kasb_uz,
            'kasb_en'               => $s->kasb_en,
            'kasb_ru'               => $s->kasb_ru,

            'boshlanish_sanasi'     => $s->boshlanish_sanasi?->format('d.m.Y'),
            'tugash_sanasi'         => $s->tugash_sanasi?->format('d.m.Y'),
            'soat'                  => (string) $s->soat,

            'direktor_fio'          => $s->direktor_fio,
            'director_fio'          => $s->direktor_fio,
            'code'                  => '',
            'registratsiya_raqami'  => $s->registratsiya_raqami,
            'registratsiya_sanasi'  => $s->registratsiya_sanasi?->format('d.m.Y'),
        ];
    }

    public function samples(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $paginator = Sertifikat::query()
            ->when($q !== '', function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('raqam', 'like', "%{$q}%")
                      ->orWhere('fio_uz', 'like', "%{$q}%")
                      ->orWhere('kasb_uz', 'like', "%{$q}%")
                      ->orWhere('kasb_ru', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json([
            'data' => $paginator->getCollection()->map(fn (Sertifikat $s) => [
                'id'           => $s->id,
                'raqam'        => $s->raqam,
                'fio'          => $s->fio_uz,
                'mutaxassislik'=> $s->kasb_uz,
                'sana'         => optional($s->tugash_sanasi)->format('d.m.Y'),
            ]),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    public function sampleData(Sertifikat $sertifikat)
    {
        return response()->json([
            'template_id'           => $sertifikat->template_id,
            'fio_uz'                => $sertifikat->fio_uz,
            'profession_id'         => $sertifikat->profession_id,
            'kasb_uz'               => $sertifikat->kasb_uz,
            'kasb_en'               => $sertifikat->kasb_en,
            'kasb_ru'               => $sertifikat->kasb_ru,
            'boshlanish_sanasi'     => optional($sertifikat->boshlanish_sanasi)->format('Y-m-d'),
            'tugash_sanasi'         => optional($sertifikat->tugash_sanasi)->format('Y-m-d'),
            'soat'                  => $sertifikat->soat,
            'direktor_fio'          => $sertifikat->direktor_fio,
            'registratsiya_raqami'  => $sertifikat->registratsiya_raqami,
            'registratsiya_sanasi'  => optional($sertifikat->registratsiya_sanasi)->format('Y-m-d'),
        ]);
    }
}