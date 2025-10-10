<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    use HasFactory;

    protected $table = 'stakeholder';
    protected $primaryKey = 'id';
    protected $fillable = [
        'region',
        'kebun',
        'desa',
        'curent_condition',
        'nama_instansi',
        'daerah_instansi',
        'nama_pic',
        'jabatan_pic',
        'nomorkontak_pic',
        'derajat_hubungan',
        'kategori',
        'tipe_stakeholder',
        'skala_kepentingan',
        'skala_pengaruh',
        'email',
        'ekspektasi_ptpn',
        'ekspektasi_stakeholder',
        'dokumenpendukung',
        'nama_pic2',
        'jabatan_pic2',
        'nomorkontak_pic2',
        'saranbagimanajemen',
        'prov_id',
        'kab_id',
        'kec_id',
        'desa_id',
        'hasil_skala',
        'email2',
        'latlong',
    ];

    
}
