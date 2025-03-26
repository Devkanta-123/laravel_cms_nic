<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhosWho extends Model
{
    use HasFactory;
    protected $table = 'whos_who';
    protected $fillable = [
        'id',
        'level_id',
        'name',
        'designation',
        'email',
        'contact',
        'profile_image',
        'district_name',  // Add this
        'block_name'      // Add this
    ];


}
