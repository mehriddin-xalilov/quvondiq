<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nomi',
            'Kategoriya',
            'Narxi',
            'Sotuv Narxi',
            'Minimal Zaxira',
            'Tavsif',
            'Yaratilgan vaqt'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category ? $product->category->name : 'Yo\'q',
            $product->price,
            $product->selling_price,
            $product->min_stock,
            $product->description,
            $product->created_at ? $product->created_at->format('d.m.Y H:i') : ''
        ];
    }
}
