<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'telegram_user_id',
        'telegram_username',
        'customer_name',
        'customer_phone',
        'customer_address',
        'items',
        'total',
        'status',
        'customer_notes',
        'admin_notes',
        'confirmed_by',
        'sale_id',
        'confirmed_at',
        'telegram_message_id',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'total' => 'decimal:2',
            'confirmed_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
