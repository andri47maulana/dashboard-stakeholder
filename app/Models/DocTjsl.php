<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocTjsl extends Model
{
    use HasFactory;

    protected $table = 'tb_doc_tjsl';
    
    // Nonaktifkan timestamps karena tabel tidak memiliki created_at dan updated_at
    public $timestamps = false;
    
    // Pastikan primary key dikonfigurasi dengan benar
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tjsl_id',
        'proposal',
        'izin_prinsip',
        'survei_feedback',
        'foto'
    ];

    protected $casts = [
        'tjsl_id' => 'integer',
        'proposal' => 'string',
        'izin_prinsip' => 'string',
        'survei_feedback' => 'string',
        'foto' => 'string'
    ];

    public function tjsl(): BelongsTo
    {
        return $this->belongsTo(Tjsl::class, 'tjsl_id');
    }

}
