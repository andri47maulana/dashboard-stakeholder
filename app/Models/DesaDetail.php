<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesaDetail extends Model
{
    use HasFactory;

    protected $table = 'tb_isu_desa';
    protected $fillable = ['derajat_id','desa_id','isu_utama'];
    public $timestamps = false;
    public function parent()
    {
        return $this->belongsTo(DerajatHubungan::class, 'derajat_id');
    }
}
