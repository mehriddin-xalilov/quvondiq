<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description',
        'image',
        'unit',
        'price',
        'cost_price',
        'min_stock_level',
        'optimal_stock_level',
        'is_active',
        'sales_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'min_stock_level' => 'decimal:2',
            'optimal_stock_level' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stock()
    {
        return $this->hasOne(WarehouseStock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereHas('stock', function ($q) {
            $q->whereRaw('quantity <= min_stock_level');
        });
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock && $this->stock->quantity <= $this->min_stock_level;
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->stock ? $this->stock->quantity - $this->stock->reserved_quantity : 0;
    }
}
