<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    protected $fillable = [
        'region_id',
        'coato_code',
        'tax_id',
        'name_uz',
        'name_oz',
        'name_ru',
        'name_en',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}