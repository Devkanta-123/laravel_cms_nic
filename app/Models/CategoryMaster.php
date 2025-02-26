<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMaster extends Model
{
    use HasFactory;
    protected $table = 'category_master';
    protected $fillable = ['id', 'category_name'];
}
