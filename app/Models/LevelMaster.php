<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelMaster extends Model
{
    use HasFactory;
    protected $table = 'level_master';
    // If  table has timestamps, keep these true (which is default)
    public $timestamps = true;
    protected $fillable = ['id', 'level_name'];
}
