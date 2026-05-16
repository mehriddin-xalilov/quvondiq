<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function index()
    {
        $professions = Profession::orderBy('name_uz')->paginate(20);

        return view('professions.index', compact('professions'));
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