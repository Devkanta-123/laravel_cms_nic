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
        'district_name',
        'block_name',
        'user_id',
        'role_id',
        'flag',
        'rejected_remarks',
        'publisher_id',
        'application_id'

    ];


}
