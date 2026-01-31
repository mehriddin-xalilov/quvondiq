<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'noteable_type',
        'noteable_id',
        'content',
        'type',
        'is_important',
        'reminder_date',
        'is_completed',
        'completed_at',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_important' => 'boolean',
            'is_completed' => 'boolean',
            'reminder_date' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function noteable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeReminders($query)
    {
        return $query->whereNotNull('reminder_date')
                     ->where('is_completed', false);
    }
}
