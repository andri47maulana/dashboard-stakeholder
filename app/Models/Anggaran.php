<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'tb_anggaran_tjsl';

    // Konfigurasi primary key
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'sub_pilar_id',
        'tahun',
        'anggaran'
    ];

    protected $casts = [
        'sub_pilar_id' => 'integer',
        'tahun' => 'string',
        'anggaran' => 'float'
    ];

    /**
     * Relasi ke model SubPilar
     */
    public function subPilar(): BelongsTo
    {
        return $this->belongsTo(SubPilar::class, 'sub_pilar_id');
    }
}
