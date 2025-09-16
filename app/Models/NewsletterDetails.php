<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterDetails extends Model
{
    use HasFactory;
    protected $table = "newsletterdetails";
    protected $fillable = [
        'newsletterid',
        'menu_id',
        'page_section_id',
        'english_title',
        'fromdate',
        'todate',
        'filepath',
        'status',
    ];
}
