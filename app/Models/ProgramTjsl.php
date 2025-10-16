<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramTjsl extends Model
{
    use HasFactory;

    protected $table = 'tb_program_tjsl';
    protected $primaryKey = 'id';
    public const UPDATED_AT = null;

    protected $fillable = [
        'regional',
        'kebun',
        'lokasi_program',
        'nama_program',
        'tgl_mulai',
        'tgl_selesai',
        'penerima',
        'tpb',
        'status',
        'anggaran',
        'realisasi',
        'persentase',
        'persentase_rka',
        'deskripsi',
        'sangat_puas',
        'puas',
        'kurang_puas',
        'pilar',
        'program',
        'employee',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'anggaran' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'persentase' => 'decimal:2',
        'persentase_rka' => 'decimal:2',
        'sangat_puas' => 'integer',
        'puas' => 'integer',
        'kurang_puas' => 'integer',
    ];
}