<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;
    protected $table = "logo";
    protected $fillable = [
        'image',
        'menu_id',
        'user_id',
        'role_id',
        'flag',
        'rejected_remarks',
        'publisher_id',
        'application_id'
    ];
}
