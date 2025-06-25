<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Paragraph extends Model
{
    use HasFactory;

    protected $table = "paragraph";

    protected $fillable = [

        'menu_id',
        'page_section_id',
        'title',
        'hindi_title',
        'khasi_title',
        'description',
        'hindi_description',
        'khasi_description',
        'new_description',
        'status',
        'flag',
        'user_id',
        'role_id',
        'rejected_remarks',
        'publisher_id',
        'application_id'


    ];

}
