<?php

namespace App\Exports;

use App\Models\StockMovement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockMovementsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $movements;

    public function __construct($movements)
    {
        $this->movements = $movements;
    }

    public function collection()
    {
        return $this->movements;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Mahsulot',
            'Turi',
            'Miqdor',
            'Izoh',
            'Foydalanuvchi',
            'Sana'
        ];
    }

    public function map($movement): array
    {
        return [
            $movement->id,
            $movement->product ? $movement->product->name : 'Noma\'lum',
            $movement->type === 'in' ? 'Kirim' : 'Chiqim',
            $movement->quantity,
            $movement->description,
            $movement->user ? $movement->user->name : 'Tizim',
            $movement->movement_date ? $movement->movement_date->format('d.m.Y H:i') : ''
        ];
    }
}
