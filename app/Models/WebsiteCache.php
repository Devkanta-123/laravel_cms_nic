<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteCache extends Model
{
    use HasFactory;
    // Specify the table name
    protected $table = 'website_cache';

    // Define fillable fields
    protected $fillable = [
        'type',
        'data',
        'carousel_id'
    ];
}
