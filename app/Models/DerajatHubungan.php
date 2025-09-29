<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;

class DerajatHubungan extends Model
{
    protected $table = 'tb_derajat_hubungan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_unit',
        'lingkungan',
        'ekonomi',
        'pendidikan',
        'sosial_kesesjahteraan',
        'okupasi',
        'skor_socmap',
        'prioritas_socmap',
        'kepuasan',
        'kontribusi',
        'komunikasi',
        'kepercayaan',
        'keterlibatan',
        'indeks_kepuasan',
        'derajat_hubungan',
        'deskripsi',
        'tahun',
        'input_date',
        'modified_date',
        'derajat_kepuasan'
    ];

    public function unitx()
    {
        return $this->belongsTo(Unit::class, 'id_unit', 'id');
    }
}
