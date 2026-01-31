<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Mijoz',
            'Umumiy Summa',
            'To\'langan Summa',
            'Qarz',
            'Status',
            'Sana'
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->id,
            $sale->customer ? $sale->customer->name : 'Noma\'lum',
            $sale->total_amount,
            $sale->paid_amount,
            $sale->due_amount,
            $sale->status,
            $sale->sale_date ? $sale->sale_date->format('d.m.Y H:i') : ''
        ];
    }
}
