<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Models\Guvohnoma;
use App\Models\Profession;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuvohnomaController extends Controller
{
    public function __construct(private readonly DocumentGenerationService $generator)
    {
    }

    public function index(Request $request)
    {
        $guvohnomalar = $this->applyFilters(
                Guvohnoma::with(['region', 'district', 'profession', 'creator']),
                $request
            )
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('guvohnomalar.index', compact('guvohnomalar'));
    }

    /**
     * Guvohnoma so'roviga qidiruv/razryad/sana filtrlarini qo'llaydi.
     * index() (sahifa) va samples() (modal) tomonidan birga ishlatiladi.
     */
    private function applyFilters($query, Request $request)
    {
        $q        = trim((string) $request->query('q', ''));
        $razryad  = trim((string) $request->query('razryad', ''));
        $dateFrom = $request->query('date_from');
        $dateTo   = $request->query('date_to');

        return $query
            ->when($q !== '', function ($w) use ($q) {
                $w->where(function ($s) use ($q) {
                    $s->where('raqam', 'like', "%{$q}%")
                      ->orWhere('familiya_ru', 'like', "%{$q}%")
                      ->orWhere('familiya_oz', 'like', "%{$q}%")
                      ->orWhere('ism_ru', 'like', "%{$q}%")
                      ->orWhere('ism_oz', 'like', "%{$q}%")
                      ->orWhere('otasi_ismi_ru', 'like', "%{$q}%")
                      ->orWhere('otasi_ismi_oz', 'like', "%{$q}%")
                      ->orWhere('mutaxassislik_oz', 'like', "%{$q}%")
                      ->orWhere('mutaxassislik_ru', 'like', "%{$q}%");
                });
            })
            ->when($razryad !== '', fn ($w) => $w->where('razryad', 'like', "%{$razryad}%"))
            ->when($dateFrom, fn ($w) => $w->whereDate('tugash_sanasi', '>=', $dateFrom))
            ->when($dateTo, fn ($w) => $w->whereDate('tugash_sanasi', '<=', $dateTo));
    }

    public function create()
    {
        return view('guvohnomalar.create', [
            'professions' => Profession::orderBy('name_uz')->get(),
            'templates'   => DocumentTemplate::active()
                ->where('type', DocumentTemplate::TYPE_GUVOHNOMA)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateInput($request);

        $template = DocumentTemplate::findOrFail($data['template_id']);
        unset($data['template_id']);

        $photo = $request->file('photo');
        $photoPath = null;
        if ($photo) {
            $photoPath = $photo->store('guvohnomalar/photos', 'public');
        }
        unset($data['photo']);

        $guvohnoma = Guvohnoma::create([
            ...$data,
            'photo_path' => $photoPath,
            'created_by' => auth()->id(),
        ]);

        $guvohnoma->guvohnoma_path = $this->generateForGuvohnoma($template, $guvohnoma);
        $guvohnoma->save();

        return redirect()
            ->route('guvohnomalar.show', $guvohnoma)
            ->with('success', 'Guvohnoma yaratildi va docx tayyor.');
    }

    public function show(Guvohnoma $guvohnoma)
    {
        $guvohnoma->load(['region', 'district', 'profession', 'creator']);
        return view('guvohnomalar.show', compact('guvohnoma'));
    }

    public function edit(Guvohnoma $guvohnoma)
    {
        return view('guvohnomalar.edit', [
            'guvohnoma'   => $guvohnoma,
            'professions' => Profession::orderBy('name_uz')->get(),
            'templates'   => DocumentTemplate::active()
                ->where('type', DocumentTemplate::TYPE_GUVOHNOMA)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Guvohnoma $guvohnoma)
    {
        $data = $this->validateInput($request, $guvohnoma->id);

        $template = DocumentTemplate::findOrFail($data['template_id']);
        unset($data['template_id']);

        if ($request->hasFile('photo')) {
            if ($guvohnoma->photo_path) {
                Storage::disk('public')->delete($guvohnoma->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('guvohnomalar/photos', 'public');
        }
        unset($data['photo']);

        if ($guvohnoma->guvohnoma_path) {
            Storage::disk('local')->delete($guvohnoma->guvohnoma_path);
        }

        $guvohnoma->update($data);

        $guvohnoma->guvohnoma_path = $this->generateForGuvohnoma($template, $guvohnoma);
        $guvohnoma->save();

        return redirect()
            ->route('guvohnomalar.show', $guvohnoma)
            ->with('success', 'Guvohnoma yangilandi va docx qayta yaratildi.');
    }

    public function destroy(Guvohnoma $guvohnoma)
    {
        if ($guvohnoma->guvohnoma_path) {
            Storage::disk('local')->delete($guvohnoma->guvohnoma_path);
        }
        $guvohnoma->delete();

        return redirect()
            ->route('guvohnomalar.index')
            ->with('success', 'Guvohnoma o\'chirildi.');
    }

    /**
     * Yangi guvohnoma formasidagi "namuna olish" modalining ro'yxati.
     * Sahifalangan JSON qaytaradi.
     */
    public function samples(Request $request)
    {
        $paginator = $this->applyFilters(Guvohnoma::query(), $request)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => $paginator->getCollection()->map(fn (Guvohnoma $g) => [
                'id'           => $g->id,
                'raqam'        => $g->raqam,
                'fio'          => $g->fullNameRu() ?: $g->fullNameOz(),
                'mutaxassislik'=> $g->mutaxassislik_ru ?: $g->mutaxassislik_oz,
                'razryad'      => $g->razryad,
                'sana'         => optional($g->tugash_sanasi)->format('d.m.Y'),
            ]),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * Tanlangan eski guvohnomadan ism/familiya/raqamdan tashqari hamma
     * maydonni formaga to'ldirish uchun JSON beradi.
     */
    public function sampleData(Guvohnoma $guvohnoma)
    {
        return response()->json([
            'region_id'                => $guvohnoma->region_id,
            'district_id'              => $guvohnoma->district_id,
            'profession_id'            => $guvohnoma->profession_id,
            'berilgan_joy_oz'          => $guvohnoma->berilgan_joy_oz,
            'berilgan_joy_ru'          => $guvohnoma->berilgan_joy_ru,
            'mutaxassislik_oz'         => $guvohnoma->mutaxassislik_oz,
            'mutaxassislik_ru'         => $guvohnoma->mutaxassislik_ru,
            'razryad'                  => $guvohnoma->razryad,
            'boshlanish_sanasi'        => optional($guvohnoma->boshlanish_sanasi)->format('Y-m-d'),
            'tugash_sanasi'            => optional($guvohnoma->tugash_sanasi)->format('Y-m-d'),
            'protokol_raqami'          => $guvohnoma->protokol_raqami,
            'komissiya_raisi_fio'      => $guvohnoma->komissiya_raisi_fio,
            'komissiya_azosi_fio'      => $guvohnoma->komissiya_azosi_fio,
            'direktor_fio'             => $guvohnoma->direktor_fio,
            'ball_umumiy_oz'           => $guvohnoma->ball_umumiy_oz,
            'ball_umumiy_ru'           => $guvohnoma->ball_umumiy_ru,
            'ball_maxsus_oz'           => $guvohnoma->ball_maxsus_oz,
            'ball_maxsus_ru'           => $guvohnoma->ball_maxsus_ru,
            'ball_ishlab_chiqarish_oz' => $guvohnoma->ball_ishlab_chiqarish_oz,
            'ball_ishlab_chiqarish_ru' => $guvohnoma->ball_ishlab_chiqarish_ru,
        ]);
    }

    public function download(Guvohnoma $guvohnoma)
    {
        abort_unless($guvohnoma->guvohnoma_path, 404);

        $ext = pathinfo($guvohnoma->guvohnoma_path, PATHINFO_EXTENSION) ?: 'docx';
        return Storage::disk('local')->download(
            $guvohnoma->guvohnoma_path,
            sprintf('Guvohnoma_%s.%s', $guvohnoma->raqam, $ext)
        );
    }

    /**
     * Docx generatsiya qiladi va Word (.docx) fayl yo'lini qaytaradi.
     * Guvohnoma PDF ga aylantirilmaydi — Word holatida qoladi.
     */
    private function generateForGuvohnoma(DocumentTemplate $template, Guvohnoma $guvohnoma): string
    {
        $photoAbsPath = null;
        if ($guvohnoma->photo_path) {
            $photoAbsPath = Storage::disk('public')->path($guvohnoma->photo_path);
        }

        return $this->generator->generate(
            $template,
            $this->buildPlaceholderMap($guvohnoma),
            'generated/guvohnomalar',
            $guvohnoma->verifyUrl(),
            $photoAbsPath
        );
    }

    private function validateInput(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'template_id'             => 'required|exists:document_templates,id',
            'raqam'                   => 'required|string|max:64|unique:guvohnomalar,raqam' . ($ignoreId ? ',' . $ignoreId : ''),

            'familiya_oz'             => 'required|string|max:255',
            'ism_oz'                  => 'required|string|max:255',
            'otasi_ismi_oz'           => 'nullable|string|max:255',

            'familiya_ru'             => 'required|string|max:255',
            'ism_ru'                  => 'required|string|max:255',
            'otasi_ismi_ru'           => 'nullable|string|max:255',

            'region_id'               => 'nullable|exists:regions,id',
            'district_id'             => 'nullable|exists:districts,id',
            'berilgan_joy_oz'         => 'nullable|string|max:255',
            'berilgan_joy_ru'         => 'nullable|string|max:255',

            'profession_id'           => 'nullable|exists:professions,id',
            'mutaxassislik_oz'        => 'required|string|max:500',
            'mutaxassislik_ru'        => 'nullable|string|max:500',
            'speciality'              => 'nullable|string|max:32',
            'razryad'                 => 'nullable|string|max:8',

            'boshlanish_sanasi'       => 'required|date',
            'tugash_sanasi'           => 'required|date|after_or_equal:boshlanish_sanasi',
            'berilgan_sanasi'         => 'nullable|date',

            'protokol_raqami'         => 'nullable|string|max:64',
            'protokol_sanasi'         => 'nullable|date',

            'komissiya_raisi_fio'     => 'required|string|max:255',
            'komissiya_azosi_fio'     => 'nullable|string|max:255',
            'direktor_fio'            => 'required|string|max:255',

            'ball_umumiy_oz'          => 'nullable|string|max:64',
            'ball_umumiy_ru'          => 'nullable|string|max:64',
            'ball_maxsus_oz'          => 'nullable|string|max:64',
            'ball_maxsus_ru'          => 'nullable|string|max:64',
            'ball_ishlab_chiqarish_oz'=> 'nullable|string|max:64',
            'ball_ishlab_chiqarish_ru'=> 'nullable|string|max:64',
            'photo'                   => 'nullable|image|max:2048',
        ]);

        // FIO ikki tilda alohida kiritiladi — guvohnomaning chap tomoni `_oz`,
        // o'ng tomoni `_ru` qiymatini chiqaradi. Faqat bo'sh qolgan tomon
        // ikkinchisidan to'ldiriladi (bazada `_oz` NOT NULL bo'lishi mumkin).
        $data['familiya_oz'] = $data['familiya_oz'] ?: $data['familiya_ru'];
        $data['ism_oz']      = $data['ism_oz'] ?: $data['ism_ru'];
        $data['familiya_ru'] = $data['familiya_ru'] ?: $data['familiya_oz'];
        $data['ism_ru']      = $data['ism_ru'] ?: $data['ism_oz'];

        $data['otasi_ismi_oz'] = ($data['otasi_ismi_oz'] ?? null) ?: ($data['otasi_ismi_ru'] ?? null);
        $data['otasi_ismi_ru'] = ($data['otasi_ismi_ru'] ?? null) ?: ($data['otasi_ismi_oz'] ?? null);

        return $data;
    }

    private function buildPlaceholderMap(Guvohnoma $g): array
    {
        $region = $g->region;
        $district = $g->district;

        $start = $g->boshlanish_sanasi;
        $end   = $g->tugash_sanasi;
        // berilgan_sanasi formada yo'q endi — tugash sanasini fallback qilib olamiz
        $issued = $g->berilgan_sanasi ?: $end;

        return [
            // --- Eski (legacy) kalitlar, ilgari yuklangan shablonlar ham ishlasin ---
            'raqam'                   => $g->raqam,
            'familiya_oz'             => $g->familiya_oz,
            'familiya_ru'             => $g->familiya_ru,
            'ism_oz'                  => $g->ism_oz,
            'ism_ru'                  => $g->ism_ru,
            'otasi_ismi_oz'           => $g->otasi_ismi_oz,
            'otasi_ismi_ru'           => $g->otasi_ismi_ru,
            'fio_oz'                  => $g->fullNameOz(),
            'fio_ru'                  => $g->fullNameRu(),
            'viloyat_oz'              => $region?->name_oz,
            'viloyat_ru'              => $region?->name_ru,
            'tuman_oz'                => $district?->name_oz,
            'tuman_ru'                => $district?->name_ru,
            'berilgan_joy_oz'         => $g->berilgan_joy_oz,
            'berilgan_joy_ru'         => $g->berilgan_joy_ru,
            'mutaxassislik_oz'        => $g->mutaxassislik_oz,
            'mutaxassislik_ru'        => $g->mutaxassislik_ru,
            'razryad'                 => $g->razryad,
            'boshlanish_sanasi'       => $start?->format('d.m.Y'),
            'tugash_sanasi'           => $end?->format('d.m.Y'),
            'berilgan_sanasi'         => $issued?->format('d.m.Y'),
            'protokol_raqami'         => $g->protokol_raqami,
            'protokol_sanasi'         => $g->protokol_sanasi?->format('d.m.Y'),
            'komissiya_raisi_fio'     => $g->komissiya_raisi_fio,
            'komissiya_azosi_fio'     => $g->komissiya_azosi_fio,
            'direktor_fio'            => $g->direktor_fio,
            'ball_umumiy_oz'          => $g->ball_umumiy_oz,
            'ball_umumiy_ru'          => $g->ball_umumiy_ru,
            'ball_maxsus_oz'          => $g->ball_maxsus_oz,
            'ball_maxsus_ru'          => $g->ball_maxsus_ru,
            'ball_ishlab_chiqarish_oz'=> $g->ball_ishlab_chiqarish_oz,
            'ball_ishlab_chiqarish_ru'=> $g->ball_ishlab_chiqarish_ru,

            // --- Yangi (shablon.docx) kalitlari ---
            'number'                  => $g->raqam,
            'name'                    => $g->ism_ru ?: $g->ism_oz,
            'surname'                 => $g->familiya_ru ?: $g->familiya_oz,
            'patronymic'              => $g->otasi_ismi_ru ?: $g->otasi_ismi_oz,
            'surname_initials'        => $g->surnameInitialsRu(),
            'surname_initials_oz'     => $g->surnameInitialsOz(),
            'surname_initials_ru'     => $g->surnameInitialsRu(),

            // Guvohnomaning o'ng (ruscha) tomoni uchun alohida kalitlar —
            // chap (o'zbekcha) tomon `_oz`, o'ng tomon `_ru` ni ishlatadi.
            'name_oz'                 => $g->ism_oz ?: $g->ism_ru,
            'surname_oz'              => $g->familiya_oz ?: $g->familiya_ru,
            'patronymic_oz'           => $g->otasi_ismi_oz ?: $g->otasi_ismi_ru,
            'name_ru'                 => $g->ism_ru ?: $g->ism_oz,
            'surname_ru'              => $g->familiya_ru ?: $g->familiya_oz,
            'patronymic_ru'           => $g->otasi_ismi_ru ?: $g->otasi_ismi_oz,

            'speciality_uz'           => $g->speciality ?: $g->mutaxassislik_oz,
            'speciality_ru'           => $g->speciality ?: ($g->mutaxassislik_ru ?: $g->mutaxassislik_oz),
            'speciality'              => $g->speciality,
            'rank'                    => $g->razryad,
            'rank_ru'                 => $this->rankWordRu($g->razryad),

            'general_uz'              => $g->ball_umumiy_oz,
            'general_ru'              => $g->ball_umumiy_ru ?: $g->ball_umumiy_oz,
            'special_uz'              => $g->ball_maxsus_oz,
            'special_ru'              => $g->ball_maxsus_ru ?: $g->ball_maxsus_oz,
            'production_uz'           => $g->ball_ishlab_chiqarish_oz,
            'production_ru'           => $g->ball_ishlab_chiqarish_ru ?: $g->ball_ishlab_chiqarish_oz,

            'start_day'               => $start?->format('d'),
            'start_month_uz'          => $this->monthNameUz($start?->month),
            'start_month_ru'          => $this->monthNameRu($start?->month),
            'start_year'              => $start?->format('Y'),

            'end_day'                 => $end?->format('d'),
            'end_month_uz'            => $this->monthNameUz($end?->month),
            'end_month_ru'            => $this->monthNameRu($end?->month),
            'end_year'                => $end?->format('Y'),

            'protocol_no'             => $g->protokol_raqami,
            'chairname'               => $g->komissiya_raisi_fio,
            'member1'                 => $g->komissiya_azosi_fio,
            'city'                    => $g->berilgan_joy_ru ?: $g->berilgan_joy_oz,
            'city_uz'                 => $g->berilgan_joy_oz ?: $g->berilgan_joy_ru,
            'issued_date'             => $issued?->format('d.m.Y'),
        ];
    }

    private function monthNameUz(?int $m): string
    {
        $names = [1=>'январ',2=>'феврал',3=>'март',4=>'апрел',5=>'май',6=>'июн',
                  7=>'июл',8=>'август',9=>'сентябр',10=>'октябр',11=>'ноябр',12=>'декабр'];
        return $m ? ($names[$m] ?? '') : '';
    }

    /**
     * `razryad` raqamini ruschasi sifatida (genitive ordinal) qaytaradi —
     * shablonda `{{rank}} ({{rank_ru}}) разряда` ko'rinishida ishlatiladi,
     * masalan: 6 → "шестого", 3 → "третьего".
     */
    private function rankWordRu(?string $razryad): string
    {
        if ($razryad === null || $razryad === '') {
            return '';
        }
        if (!preg_match('/^(\d+)/', trim($razryad), $m)) {
            return $razryad;
        }
        $words = [
            1=>'первого',2=>'второго',3=>'третьего',4=>'четвёртого',5=>'пятого',6=>'шестого',
            7=>'седьмого',8=>'восьмого',9=>'девятого',10=>'десятого',11=>'одиннадцатого',
            12=>'двенадцатого',13=>'тринадцатого',14=>'четырнадцатого',15=>'пятнадцатого',
        ];
        return $words[(int) $m[1]] ?? $razryad;
    }

    private function monthNameRu(?int $m): string
    {
        $names = [1=>'января',2=>'февраля',3=>'марта',4=>'апреля',5=>'мая',6=>'июня',
                  7=>'июля',8=>'августа',9=>'сентября',10=>'октября',11=>'ноября',12=>'декабря'];
        return $m ? ($names[$m] ?? '') : '';
    }
}