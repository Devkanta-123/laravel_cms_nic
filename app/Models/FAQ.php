<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;
    protected $table = 'faqs';
    protected $fillable = ['id','english_title_question','english_answer','order','user_id', 'role_id','flag', 'rejected_remarks', 'publisher_id','application_id'];
}
