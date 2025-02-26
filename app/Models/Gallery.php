<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    public function parentGallery()
    {
        return $this->hasOne('App\Models\Gallery', 'id', 'parent_id')->where('status', 1);
    }

    public function subGallery()
    {
        return $this->hasMany('App\Models\Gallery', 'parent_id')->where('status', 1);
    }

    public static function getGallery()
    {
        $getGallery = Gallery::with('subGallery')->where('parent_id', 0)->where('status', 1)->get();
        return $getGallery;
    }
    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'parent_id',
        'gallery_name',
        'gallery_image',
        'gallery_description',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'order',
        'status',
        'hindi_name',
        'khasi_name',
        'other_name',
        'hindi_description',
        'khasi_description',
        'other_description',
    ];
}
