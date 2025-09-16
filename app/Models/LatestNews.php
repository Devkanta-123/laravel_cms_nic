<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestNews extends Model
{
    use HasFactory;

    protected $table = "latest_news";
    public $timestamps = true;
    protected $fillable = [
        'file',
        'link',
        'type',
        'title',
        'order',
        'status',
        'page',
        'pagename',
        'pagemenuid',
        'user_id',
        'role_id',
        'flag',
        'rejected_remarks',
        'publisher_id',
        'application_id'


    ];
}
