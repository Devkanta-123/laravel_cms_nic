<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;
    protected $table = 'faqs';
    protected $fillable = ['id','english_title_question','hindi_title_question', 'khasi_title_question','english_answer','hindi_answer','khasi_answer','order','user_id', 'role_id','flag'];
}
