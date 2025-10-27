<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackTjsl extends Model
{
    use HasFactory;

    protected $table = 'tb_feedback_tjsl';
    
    // Nonaktifkan timestamps karena tabel tidak memiliki created_at dan updated_at
    public $timestamps = false;
    
    // Pastikan primary key dikonfigurasi dengan benar
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tjsl_id',
        'sangat_puas',
        'puas',
        'kurang_puas',
        'saran'
    ];

    protected $casts = [
        'tjsl_id' => 'integer',
        'sangat_puas' => 'boolean',
        'puas' => 'boolean',
        'kurang_puas' => 'boolean',
        'saran' => 'string',
    ];

    public function tjsl(): BelongsTo
    {
        return $this->belongsTo(Tjsl::class, 'tjsl_id');
    }

}
