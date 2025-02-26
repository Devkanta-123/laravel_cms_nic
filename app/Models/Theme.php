<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Theme extends Model
{
    use HasFactory;
    protected $table = 'theme';

    protected $fillable = [
        'theme',
 
    ];

    public function theme_master()
    {
        return $this->belongsTo(ThemeMaster::class,'theme');
    }
}
