<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function collection()
    {
        return $this->categories;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nomi',
            'Ota Kategoriya',
            'Tavsif',
            'Status',
            'Yaratilgan vaqt'
        ];
    }

    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->parent ? $category->parent->name : 'Asosiy',
            $category->description,
            $category->is_active ? 'Faol' : 'Nofaol',
            $category->created_at ? $category->created_at->format('d.m.Y H:i') : ''
        ];
    }
}
