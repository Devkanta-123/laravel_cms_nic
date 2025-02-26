<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = "menu";
    public $timestamps = false;
    protected $fillable = [
        'menu_name',
        'menu_master',
        'order',
        'parent',
        'hindi_name',
        'khasi_name',
        'other_name'
    ];

    public function menu_master()
    {
        return $this->belongsTo(MenuMaster::class, 'menu_master', 'id');
    }

    public function parentMenu()
    {
        return $this->hasOne(Menu::class, 'id', 'parent')->select('id', 'menu_name')->where('status', 1);
    }

    public function subMenus()
    {
        return $this->hasMany(Menu::class, 'parent')->where('status', 1);
    }
    // public static function getMenus(){
    //     return Menu::with('submenus')->where('parent',0)->where('status',1)->get();
    // }

    public static function getMenus()
    {
        return Menu::with([
            'submenus' => function ($query) {
                $query->with('submenus');
            }
        ])
            ->where('parent', 0)
            ->where('status', 1)
            ->where('menu_master', 1)
            ->orderBy('menu.id')->get();
    }

    public static function getLeftMenus()
    {
        return Menu::with([
            'submenus' => function ($query) {
                $query->with('submenus');
            }
        ])->where('parent', 0)->where('status', 1)->where('menu_master', 2)->orderBy('menu.id')->get();
    }

    public static function getRightMenus()
    {
        return Menu::with([
            'submenus' => function ($query) {
                $query->with('submenus');
            }
        ])->where('parent', 0)->where('status', 1)->where('menu_master', 3)->orderBy('menu.id')->get();
    }

    public static function getPageMenu($id)
    {
        $data = Menu::where('parent', $id)->where('status', 1)->get();
        return $data;
    }



}
