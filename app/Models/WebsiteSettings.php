<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSettings extends Model
{
    use HasFactory;
    protected $table = 'website_settings';
    protected $fillable = ['archieve_duration', 'language_id', 'primary_color', 'secondary_color','visit_count'];
}
