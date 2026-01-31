<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'source',
        'subtotal',
        'discount',
        'discount_percent',
        'total',
        'payment_type',
        'paid_cash',
        'paid_card',
        'paid_total',
        'debt_amount',
        'status',
        'notes',
        'telegram_message_id',
        'sale_date',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_cash' => 'decimal:2',
            'paid_card' => 'decimal:2',
            'paid_total' => 'decimal:2',
            'debt_amount' => 'decimal:2',
            'sale_date' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->invoice_number)) {
                $sale->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(
                    Sale::withTrashed()->whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function debt()
    {
        return $this->hasOne(Debt::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('sale_date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('sale_date', date('Y'))
                     ->whereMonth('sale_date', date('m'));
    }
}
