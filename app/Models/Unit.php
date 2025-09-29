<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'tb_unit';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['unit', 'region'];
}

