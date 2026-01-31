<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'telegram_id',
        'telegram_username',
        'address',
        'notes',
        'is_regular',
        'total_debt',
        'total_purchases',
        'total_orders',
        'last_order_at',
        'telegram_notifications',
    ];

    protected function casts(): array
    {
        return [
            'is_regular' => 'boolean',
            'telegram_notifications' => 'boolean',
            'total_debt' => 'decimal:2',
            'total_purchases' => 'decimal:2',
            'last_order_at' => 'datetime',
        ];
    }

    // Relationships
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function telegramOrders()
    {
        return $this->hasMany(TelegramOrder::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    // Scopes
    public function scopeRegular($query)
    {
        return $query->where('is_regular', true);
    }

    public function scopeWithDebts($query)
    {
        return $query->where('total_debt', '>', 0);
    }

    // Accessors
    public function getHasDebtAttribute()
    {
        return $this->total_debt > 0;
    }
}
