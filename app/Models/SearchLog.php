<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $table = 'search_logs';
    protected $fillable = [
        'user_id',
        'title',
        'lat',
        'lng',
        'radius_km',
        'is_inside',
        'inside_unit',
        'inside_unit_id',
        'nearest_unit',
        'nearest_unit_id',
        'nearest_distance_km',
        'stakeholder_id',
        'tjsl_id',
    ];
}
