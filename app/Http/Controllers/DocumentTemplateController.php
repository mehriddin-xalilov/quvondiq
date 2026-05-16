<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentTemplateController extends Controller
{
    public function __construct(private readonly DocumentGenerationService $generator)
    {
    }

    public function index()
    {
        $templates = DocumentTemplate::with('creator')
            ->orderByDesc('id')
            ->paginate(15);

        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:guvohnoma,certificate',
            'description' => 'nullable|string',
            'file'        => 'required|file|mimes:docx|max:10240',
            'is_active'   => 'nullable|boolean',
        ]);

        $slug = $this->uniqueSlug($validated['name']);
        $path = $request->file('file')->storeAs(
            'templates',
            $slug . '_' . now()->format('Ymd_His') . '.docx',
            'local'
        );

        $template = DocumentTemplate::create([
            'name'              => $validated['name'],
            'slug'              => $slug,
            'type'              => $validated['type'],
            'description'       => $validated['description'] ?? null,
            'file_path'         => $path,
            'original_filename' => $request->file('file')->getClientOriginalName(),
            'created_by'        => auth()->id(),
            'is_active'         => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()
            ->route('templates.show', $template)
            ->with('success', 'Shablon muvaffaqiyatli yuklandi.');
    }

    public function show(DocumentTemplate $template)
    {
        $placeholders = $this->generator->extractPlaceholders(
            Storage::disk('local')->path($template->file_path)
        );

        return view('templates.show', compact('template', 'placeholders'));
    }

    public function edit(DocumentTemplate $template)
    {
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, DocumentTemplate $template)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:guvohnoma,certificate',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:docx|max:10240',
            'is_active'   => 'nullable|boolean',
        ]);

        $data = [
            'name'        => $validated['name'],
            'type'        => $validated['type'],
            'description' => $validated['description'] ?? null,
            'is_active'   => (bool) ($validated['is_active'] ?? false),
        ];

        if ($request->hasFile('file')) {
            Storage::disk('local')->delete($template->file_path);
            $data['file_path'] = $request->file('file')->storeAs(
                'templates',
                $template->slug . '_' . now()->format('Ymd_His') . '.docx',
                'local'
            );
            $data['original_filename'] = $request->file('file')->getClientOriginalName();
        }

        $template->update($data);

        return redirect()
            ->route('templates.show', $template)
            ->with('success', 'Shablon yangilandi.');
    }

    public function destroy(DocumentTemplate $template)
    {
        $template->delete();

        return redirect()
            ->route('templates.index')
            ->with('success', 'Shablon o\'chirildi.');
    }

    public function download(DocumentTemplate $template)
    {
        return Storage::disk('local')->download(
            $template->file_path,
            $template->original_filename
        );
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'shablon';
        $slug = $base;
        $i = 1;
        while (DocumentTemplate::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}