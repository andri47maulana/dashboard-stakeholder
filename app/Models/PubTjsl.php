<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PubTjsl extends Model
{
    use HasFactory;

    protected $table = 'tb_pub_tjsl';

    protected $fillable = [
        'tjsl_id',
        'media',
        'link'
    ];

    protected $casts = [
        'tjsl_id' => 'integer',
        'media' => 'string',
        'link' => 'string'
    ];

    public function tjsl(): BelongsTo
    {
        return $this->belongsTo(Tjsl::class, 'tjsl_id');
    }

}
