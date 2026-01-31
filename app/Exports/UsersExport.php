<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Ism',
            'Foydalanuvchi nomi',
            'Telefon',
            'Rollar',
            'Yaratilgan vaqt'
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->username,
            $user->phone,
            $user->roles->pluck('name')->implode(', '),
            $user->created_at ? $user->created_at->format('d.m.Y H:i') : ''
        ];
    }
}
