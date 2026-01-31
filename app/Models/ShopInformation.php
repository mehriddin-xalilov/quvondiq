<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopInformation extends Model
{
    protected $fillable = [
        'name',
        'logo_path',
        'address',
        'phone',
        'email',
        'telegram_bot_token',
        'telegram_chat_id',
    ];
    //
}
