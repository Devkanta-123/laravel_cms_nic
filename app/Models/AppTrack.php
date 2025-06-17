<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTrack extends Model
{
    use HasFactory;
     protected $table="app_track";
    public $timestamps=true;
    protected $fillable = [
        'menu_id',
        'page_section_master_id',
        'user_from',
        'user_from_name',
        'user_to',
        'user_to_name',
        'remarks',
        'flag',
        'action'
    ];
}
