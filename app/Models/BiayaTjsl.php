<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiayaTjsl extends Model
{
    use HasFactory;

    protected $table = 'tb_biaya_tjsl';
    
    // Nonaktifkan timestamps karena tabel tidak memiliki created_at dan updated_at
    public $timestamps = false;
    
    // Pastikan primary key dikonfigurasi dengan benar
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tjsl_id',
        'anggaran',
        'realisasi'
    ];

    protected $casts = [
        'tjsl_id' => 'integer',
        'anggaran' => 'decimal:2',
        'realisasi' => 'decimal:2'
    ];

    public function tjsl(): BelongsTo
    {
        return $this->belongsTo(Tjsl::class, 'tjsl_id');
    }

}
