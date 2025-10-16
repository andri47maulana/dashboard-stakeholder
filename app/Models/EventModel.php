<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventModel extends Model
{
    protected $table = 'tb_event';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['judul', 'deskripsi', 'start_event', 'end_event','region','unit','kat_stakeholder','tipe_event','date_created','date_modified'];
}
