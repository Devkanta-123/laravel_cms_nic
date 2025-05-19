<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;
        protected $table = 'gallery_item';
        protected $fillable = [
        'gallery_id',
        'name',
        'image'
    ];

}
