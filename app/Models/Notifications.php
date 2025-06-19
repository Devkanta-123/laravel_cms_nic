<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;
    protected $table = "notifications";
    protected $fillable = ['id', 'category_id', 'title', 'date', 'file','user_id','role_id', 'flag','rejected_remarks','publisher_id','application_id'];

}
