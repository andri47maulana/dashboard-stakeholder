<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubPilar extends Model
{
    use HasFactory;

    protected $table = 'm_sub_pilar';

    protected $fillable = [
        'pilar_id',
        'sub_pilar'
    ];

    // Relasi untuk anggaran
    public function anggarans(): HasMany
    {
        return $this->hasMany(Anggaran::class, 'sub_pilar_id');
    }

    // public function tjsl(): HasMany
    // {
    //     return $this->hasMany(Tjsl::class, 'pilar_id');
    // }
}
