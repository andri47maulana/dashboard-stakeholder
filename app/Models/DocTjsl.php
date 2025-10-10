<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocTjsl extends Model
{
    use HasFactory;

    protected $table = 'tb_doc_tjsl';

    protected $fillable = [
        'tjsl_id',
        'nama_dokumen',
        'link'
    ];

    protected $casts = [
        'tjsl_id' => 'integer',
        'nama_dokumen' => 'string',
        'link' => 'string'
    ];

    public function tjsl(): BelongsTo
    {
        return $this->belongsTo(Tjsl::class, 'tjsl_id');
    }

}
