<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    public function collection()
    {
        return $this->expenses;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kategoriya',
            'Summa',
            'Izoh',
            'Yaratuvchi',
            'Sana'
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->id,
            $expense->category ? $expense->category->name : 'Noma\'lum',
            $expense->amount,
            $expense->description,
            $expense->user ? $expense->user->name : 'Tizim',
            $expense->expense_date ? $expense->expense_date->format('d.m.Y H:i') : ''
        ];
    }
}
