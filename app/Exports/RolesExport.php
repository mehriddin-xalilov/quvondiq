<?php

namespace App\Exports;

use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RolesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $roles;

    public function __construct($roles)
    {
        $this->roles = $roles;
    }

    public function collection()
    {
        return $this->roles;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom',
            'Ruxsatlar',
            'Yaratilgan vaqt'
        ];
    }

    public function map($role): array
    {
        return [
            $role->id,
            $role->name,
            $role->permissions->pluck('name')->implode(', '),
            $role->created_at ? $role->created_at->format('d.m.Y H:i') : ''
        ];
    }
}
