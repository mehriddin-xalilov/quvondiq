<?php

namespace App\Exports;

use App\Models\TelegramOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TelegramOrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Mijoz Ismi',
            'Telefon',
            'Manzil',
            'Summa',
            'Status',
            'Yaratilgan vaqt'
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->customer_name,
            $order->customer_phone,
            $order->customer_address,
            $order->total,
            $order->status,
            $order->created_at ? $order->created_at->format('d.m.Y H:i') : ''
        ];
    }
}
