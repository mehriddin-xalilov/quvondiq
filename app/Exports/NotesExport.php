<?php

namespace App\Exports;

use App\Models\Note;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NotesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $notes;

    public function __construct($notes)
    {
        $this->notes = $notes;
    }

    public function collection()
    {
        return $this->notes;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Matn',
            'Muhim',
            'Bajarilgan',
            'Eslatma Sanasi',
            'Yaratilgan vaqt'
        ];
    }

    public function map($note): array
    {
        return [
            $note->id,
            $note->content,
            $note->is_important ? 'Ha' : 'Yo\'q',
            $note->is_completed ? 'Ha' : 'Yo\'q',
            $note->reminder_date ? $note->reminder_date->format('d.m.Y') : '',
            $note->created_at ? $note->created_at->format('d.m.Y H:i') : ''
        ];
    }
}
