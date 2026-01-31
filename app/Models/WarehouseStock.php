<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'reserved_quantity',
        'last_stock_in_at',
        'last_stock_out_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'reserved_quantity' => 'decimal:2',
            'last_stock_in_at' => 'datetime',
            'last_stock_out_at' => 'datetime',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }
}
