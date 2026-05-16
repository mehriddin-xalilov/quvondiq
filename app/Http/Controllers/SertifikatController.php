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

        $sertifikat->certificate_path = $this->generator->generate(
            $template,
            $this->buildPlaceholderMap($sertifikat),
            'generated/sertifikatlar'
        );
        $sertifikat->save();

        return redirect()
            ->route('sertifikatlar.show', $sertifikat)
            ->with('success', 'Sertifikat yaratildi va docx tayyor.');
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

        $sertifikat->certificate_path = $this->generator->generate(
            $template,
            $this->buildPlaceholderMap($sertifikat),
            'generated/sertifikatlar'
        );
        $sertifikat->save();

        return redirect()
            ->route('sertifikatlar.show', $sertifikat)
            ->with('success', 'Sertifikat yangilandi va docx qayta yaratildi.');
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

        return Storage::disk('local')->download(
            $sertifikat->certificate_path,
            sprintf('Sertifikat_%s.docx', $sertifikat->raqam)
        );
    }

    private function validateInput(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'template_id'           => 'required|exists:document_templates,id',
            'seria'                 => 'nullable|string|max:8',
            'raqam'                 => 'required|string|max:64|unique:sertifikatlar,raqam' . ($ignoreId ? ',' . $ignoreId : ''),

            'familiya_uz'           => 'required|string|max:255',
            'familiya_en'           => 'nullable|string|max:255',
            'familiya_ru'           => 'nullable|string|max:255',
            'ism_uz'                => 'required|string|max:255',
            'ism_en'                => 'nullable|string|max:255',
            'ism_ru'                => 'nullable|string|max:255',
            'otasi_ismi_uz'         => 'nullable|string|max:255',
            'otasi_ismi_en'         => 'nullable|string|max:255',
            'otasi_ismi_ru'         => 'nullable|string|max:255',

            'region_id'             => 'nullable|exists:regions,id',
            'district_id'           => 'nullable|exists:districts,id',
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
        $region = $s->region;
        $district = $s->district;

        return [
            'seria'                 => $s->seria,
            'raqam'                 => $s->raqam,

            'familiya_uz'           => $s->familiya_uz,
            'familiya_en'           => $s->familiya_en,
            'familiya_ru'           => $s->familiya_ru,
            'ism_uz'                => $s->ism_uz,
            'ism_en'                => $s->ism_en,
            'ism_ru'                => $s->ism_ru,
            'otasi_ismi_uz'         => $s->otasi_ismi_uz,
            'otasi_ismi_en'         => $s->otasi_ismi_en,
            'otasi_ismi_ru'         => $s->otasi_ismi_ru,

            'fio_uz'                => trim("{$s->familiya_uz} {$s->ism_uz} {$s->otasi_ismi_uz}"),
            'fio_en'                => trim("{$s->familiya_en} {$s->ism_en} {$s->otasi_ismi_en}"),
            'fio_ru'                => trim("{$s->familiya_ru} {$s->ism_ru} {$s->otasi_ismi_ru}"),

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
            'registratsiya_raqami'  => $s->registratsiya_raqami,
            'registratsiya_sanasi'  => $s->registratsiya_sanasi?->format('d.m.Y'),
        ];
    }
}