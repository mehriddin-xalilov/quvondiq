<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'coato_code',
        'tax_id',
        'name_uz',
        'name_oz',
        'name_ru',
        'name_en',
    ];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}