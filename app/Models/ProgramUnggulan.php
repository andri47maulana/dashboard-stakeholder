<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubPilar;

class ProgramUnggulan extends Model
{
    use HasFactory;

    protected $table = 'm_program_unggulan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'program_unggulan',
        'sub_pilar',
    ];

    protected $casts = [
        'sub_pilar' => 'array',
    ];

    // Ambil daftar SubPilar berdasarkan ID pada kolom sub_pilar (JSON)
    public function subPilars()
    {
        return SubPilar::whereIn('id', $this->sub_pilar ?? [])->get();
    }
}
