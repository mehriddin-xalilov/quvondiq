<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function collection()
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Ism',
            'Telefon',
            'Manzil',
            'Umumiy Savdo',
            'Qarzdorlik',
            'Yaratilgan vaqt'
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->phone,
            $customer->address,
            $customer->total_sales ?? 0,
            $customer->balance ?? 0,
            $customer->created_at ? $customer->created_at->format('d.m.Y H:i') : ''
        ];
    }
}
