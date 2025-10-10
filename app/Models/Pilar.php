<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pilar extends Model
{
    use HasFactory;

    protected $table = 'm_pilar';

    protected $fillable = [
        'pilar'
    ];

    public function tjsl(): HasMany
    {
        return $this->hasMany(Tjsl::class, 'pilar_id');
    }
}
