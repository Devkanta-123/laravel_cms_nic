<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;
    protected $table = 'map';

    protected $fillable = ['id','iframe', 'user_id','role_id','flag','rejected_remarks','publisher_id','application_id'];
}
