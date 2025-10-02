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

    // public function unitx()
    // {
    //     return $this->belongsTo(Unit::class, 'id_unit', 'id');
    // }

    // // Relasi ke isu detail
    // public function isu()
    // {
    //     return $this->hasMany(IsuDetail::class, 'isu_desa_id');
    // }

    // // Relasi ke desa
    // public function desa()
    // {
    //     return $this->hasMany(DesaDetail::class, 'isu_desa_id');
    // }

    // // Relasi ke instansi
    // public function instansi()
    // {
    //     return $this->hasMany(InstansiDetail::class, 'isu_desa_id');
    // }
    public function unitx()
    {
        return $this->belongsTo(Unit::class, 'id_unit', 'id');
    }

    // Relasi ke tabel isu detail
    public function isuDetail()
    {
        return $this->hasMany(IsuDetail::class, 'derajat_id', 'id');
    }

    // Relasi ke tabel desa
    public function isuDesa()
    {
        return $this->hasMany(DesaDetail::class, 'derajat_id', 'id');
    }

    // Relasi ke tabel instansi
    public function isuInstansi()
    {
        return $this->hasMany(InstansiDetail::class, 'derajat_id', 'id');
    }

    // Relasi ke tabel okupasi
    public function isuOkupasi()
    {
        return $this->hasMany(OkupasiDetail::class, 'derajat_id', 'id');
    }
}
