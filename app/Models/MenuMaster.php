<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuMaster extends Model
{
    use HasFactory;

    protected $table = "menu_master";

    public $timestamps = false;

    public function menus()  
    {  
        return $this->hasMany(Menu::class, 'menu_master', 'id');
    }  
}
