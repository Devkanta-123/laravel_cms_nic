<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;
    protected $table = "newsletter";

    protected $fillable = [
        'menu_id',
        'page_section_id',
        'english_title',
        'hindi_title',
        'khasi_title',
        'date',
        'status',
        'updated_at'
    ];
}
