<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sertifikat extends Model
{
    use SoftDeletes;

    protected $table = 'sertifikatlar';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'boshlanish_sanasi'    => 'date',
        'tugash_sanasi'        => 'date',
        'registratsiya_sanasi' => 'date',
        'soat'                 => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->verify_code)) {
                do {
                    $code = strtoupper(Str::random(10));
                } while (static::where('verify_code', $code)->exists());

                $model->verify_code = $code;
            }
        });
    }

    public function verifyUrl(): string
    {
        return route('sertifikat.verify', $this->verify_code);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}