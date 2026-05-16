<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfessionController extends Controller
{
    public function index(Request $request)
    {
        $query = Profession::query();

        if ($search = $request->string('q')->trim()->toString()) {
            $op = DB::connection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';
            $query->where(function ($q) use ($search, $op) {
                $q->where('name_uz', $op, "%{$search}%")
                  ->orWhere('name_oz', $op, "%{$search}%")
                  ->orWhere('name_ru', $op, "%{$search}%")
                  ->orWhere('name_en', $op, "%{$search}%")
                  ->orWhere('code', $op, "%{$search}%");
            });
        }

        $professions = $query->orderBy('name_uz')->paginate(20)->withQueryString();

        return view('professions.index', compact('professions', 'search'));
    }

    public function create()
    {
        return view('professions.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateInput($request);
        Profession::create($data);

        return redirect()->route('professions.index')
            ->with('success', 'Mutaxassislik qo\'shildi.');
    }

    public function edit(Profession $profession)
    {
        return view('professions.edit', compact('profession'));
    }

    public function update(Request $request, Profession $profession)
    {
        $data = $this->validateInput($request, $profession->id);
        $profession->update($data);

        return redirect()->route('professions.index')
            ->with('success', 'Mutaxassislik yangilandi.');
    }

    public function destroy(Profession $profession)
    {
        $profession->delete();

        return redirect()->route('professions.index')
            ->with('success', 'Mutaxassislik o\'chirildi.');
    }

    private function validateInput(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'code'    => 'nullable|string|max:32|unique:professions,code' . ($ignoreId ? ',' . $ignoreId : ''),
            'name_uz' => 'required|string|max:255',
            'name_oz' => 'nullable|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);
    }
}