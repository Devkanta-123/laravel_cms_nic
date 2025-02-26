<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveNews extends Model
{
    use HasFactory;
    protected $table="archive_news";
    public $timestamps=true;
    protected $fillable = [
        'file',
        'link',
        'type',
        'title',
        'order',
        'status',
        'hindi_title',
        'khasi_title',
        'other_title'

    ];
}
