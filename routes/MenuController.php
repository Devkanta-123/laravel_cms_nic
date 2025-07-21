<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuMaster;
use Illuminate\Http\Request;
use App\Models\PageSectionMaster;
class MenuController extends Controller
{

    public function index()
    {
        $menus = Menu::select('menu.*', 'parent_menu.menu_name as parent_name')
            ->leftJoin('menu as parent_menu', 'menu.parent', '=', 'parent_menu.id')
            ->with('menu_master')
            ->orderBy('menu_master')
            ->get();
        // dd($menus);
        return $menus;
    }

    public function MenuTypes()
    {
        return MenuMaster::all();
    }

    public function get_page_section_master()
    {
        $pageSectionMaster = PageSectionMaster::all();
        return response()->json($pageSectionMaster);
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'menu_name' => 'required',
            'menu_type' => 'required',
            'order' => 'required',
            'parent' => 'required',
        ]);

        $new_menu = Menu::create([
            'menu_name' => $request->menu_name,
            'menu_master' => $request->menu_type,
            'order' => $request->order,
            'parent' => $request->parent,
        ]);

        $menu = Menu::with('menu_master')->where('id', $new_menu->id)->get();

        return $menu;
    }



    public function update(Request $request, Menu $menu)
    {

        // $request->validate([
        //     'menu_name' => 'required',
        //     'menu_type' => 'required',
        //     'order' => 'required',
        //     'parent' => 'required',

        // ]);
        $menu->update([
            'menu_name' => $request->menu_name,
            'menu_master' => $request->menu_type,
            'order' => $request->order,
            'parent' => $request->parent,

        ]);
        return $menu;
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->noContent();
    }


    // public function change_role(Request $request, Menu $menu)
    // {
    //     $menu->update([
    //         'menu_name' => $request->menu_name,
    //         'menu_master' => $request->menu_type
    //     ]);
    //     return response()->json(['success' => true]);
    // }


    //to get the menu which is page type id  4 is the page type


}
