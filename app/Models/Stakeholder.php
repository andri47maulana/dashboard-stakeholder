<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    use HasFactory;

    protected $table = 'stakeholder';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_instansi'];

    
}
