<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeMaster extends Model
{
    use HasFactory;
    protected $table = 'theme_master';

    public function theme()
    {
        return $this->hasMany(Theme::class);
    }

}
