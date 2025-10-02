<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstansiDetail extends Model
{
    use HasFactory;

    protected $table = 'tb_isu_instansi';
    protected $fillable = ['derajat_id','instansi_id','program'];
    public $timestamps = false;
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class, 'stakeholder_id');
    }

    public function parent()
    {
        return $this->belongsTo(DerajatHubungan::class, 'derajat_id');
    }
}
