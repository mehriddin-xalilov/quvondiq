<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Guvohnoma extends Model
{
    use SoftDeletes;

    protected $table = 'guvohnomalar';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'boshlanish_sanasi' => 'date',
        'tugash_sanasi'     => 'date',
        'berilgan_sanasi'   => 'date',
        'protokol_sanasi'   => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->verify_code)) {
                do {
                    $code = strtoupper(Str::random(12));
                } while (static::where('verify_code', $code)->exists());

                $model->verify_code = $code;
            }
        });
    }

    public function verifyUrl(): string
    {
        // Eski yozuvlarda verify_code bo'lmasligi mumkin — avval generate qilamiz
        if (empty($this->verify_code)) {
            do {
                $code = strtoupper(\Illuminate\Support\Str::random(12));
            } while (static::where('verify_code', $code)->exists());

            $this->verify_code = $code;
            $this->saveQuietly();
        }

        return route('certificate.verify', $this->verify_code);
    }

    public function fullNameOz(): string
    {
        return trim("{$this->familiya_oz} {$this->ism_oz} {$this->otasi_ismi_oz}");
    }

    public function fullNameRu(): string
    {
        return trim("{$this->familiya_ru} {$this->ism_ru} {$this->otasi_ismi_ru}");
    }

    public function surnameInitialsRu(): string
    {
        $i = mb_substr($this->ism_ru ?? '', 0, 1);
        $o = mb_substr($this->otasi_ismi_ru ?? '', 0, 1);
        return trim($this->familiya_ru . ($i ? " {$i}." : '') . ($o ? "{$o}." : ''));
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