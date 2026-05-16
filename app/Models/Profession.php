<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    protected $fillable = [
        'code',
        'name_uz',
        'name_oz',
        'name_ru',
        'name_en',
    ];
}