<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OkupasiDetail extends Model
{
    protected $table = 'tb_isu_okupasi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'derajat_id',
        'okupasi',
        'keterangan'
    ];

    // Relasi balik ke DerajatHubungan
    public function derajat()
    {
        return $this->belongsTo(DerajatHubungan::class, 'derajat_id', 'id');
    }
}
