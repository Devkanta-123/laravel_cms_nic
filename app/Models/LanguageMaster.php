<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageMaster extends Model
{
    use HasFactory;
    protected $table='language_master';
    protected $fillable = ['language_name'];
}
