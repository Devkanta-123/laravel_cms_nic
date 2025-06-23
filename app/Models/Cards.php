<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;
    protected $table = "cards";
    protected $fillable = [
        'image',
        'menu_id',
        'page_section_id',
        'card_title',
        'card_description',
        'image',
        'more_link',
        'order',
        'status',
        'hindi_title',
        'khasi_title',
        'other_title',
        'hindi_description',
        'khasi_description',
        'other_description',
        'user_id',
        'role_id',
        'flag',
        'rejected_remarks',
        'publisher_id',
        'application_id'
    ];
}
