<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsuDetail extends Model
{
    use HasFactory;

    protected $table = 'tb_isu_detail';
    protected $fillable = ['derajat_id','isu','keterangan'];
    public $timestamps = false;
    public function parent()
    {
        return $this->belongsTo(DerajatHubungan::class, 'derajat_id');
    }
}
