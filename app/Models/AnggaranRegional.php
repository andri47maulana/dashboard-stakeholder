<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggaranRegional extends Model
{
    use HasFactory;

    // Ganti jika nama tabel berbeda
    protected $table = 'tb_anggaran_regional';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'sub_pilar_id',
        'tahun',
        'anggaran',
        'regional_id',
    ];

    protected $casts = [
        'sub_pilar_id' => 'integer',
        'tahun' => 'string',
        'anggaran' => 'integer',
        'regional_id' => 'integer',
    ];

    public function subPilar(): BelongsTo
    {
        return $this->belongsTo(SubPilar::class, 'sub_pilar_id');
    }
}
