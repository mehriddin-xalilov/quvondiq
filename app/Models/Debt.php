<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sale_id',
        'amount',
        'paid_amount',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && 
               $this->due_date < now() && 
               $this->status != 'paid';
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', '!=', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', '!=', 'paid');
    }
}
