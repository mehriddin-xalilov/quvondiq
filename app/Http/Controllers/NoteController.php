<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Exports\NotesExport;
use Maatwebsite\Excel\Facades\Excel;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', auth()->id())
                     ->orderBy('is_important', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->paginate(20);

        return view('notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'is_important' => 'boolean',
            'reminder_date' => 'nullable|date',
        ]);

        $note = new Note();
        $note->content = $validated['content'];
        $note->is_important = $request->has('is_important');
        $note->reminder_date = $validated['reminder_date'];
        $note->user_id = auth()->id();
        $note->type = 'note'; // Default type
        $note->save();

        return redirect()->route('notes.index')->with('success', 'Eslatma qo\'shildi');
    }

    public function update(Request $request, Note $note)
    {
        // Policy check? For now assuming user owns note.
        if ($note->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'is_important' => 'boolean',
            'reminder_date' => 'nullable|date',
            'is_completed' => 'boolean',
        ]);

        $note->content = $validated['content'];
        $note->is_important = $request->has('is_important');
        $note->reminder_date = $validated['reminder_date'];
        
        if ($request->has('is_completed')) {
            $note->is_completed = true;
            $note->completed_at = now();
        } else {
             $note->is_completed = false;
             $note->completed_at = null;
        }

        $note->save();

        return redirect()->route('notes.index')->with('success', 'Eslatma yangilandi');
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== auth()->id()) {
            abort(403);
        }
        
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Eslatma o\'chirildi');
    }

    public function export()
    {
        $notes = Note::where('user_id', auth()->id())
                     ->orderBy('is_important', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->take(50)
                     ->get();

        return Excel::download(new NotesExport($notes), 'notes.xlsx');
    }
}
