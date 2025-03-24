<?php

namespace App\Http\Controllers;

use App\Models\PageSection;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\PageSectionMaster;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function index()
    {
        // $pages = Pages::get();
        // // $menus = Menu::get();
        // $menus = Menu::getMenus();

        // return array($pages, $menus);
    }

    public function get_page_details(Request $request, Menu $menu)
    {

        $pageSections = PageSection::with('pageSectionMaster')->where('menu_id', $menu->id)->get();
        return $pageSections;
        // if($menu->id == 1)
        // {
        //     //Carousel
        //     //Latest News
        //     //Website Title, description
        //     //Cards
        //     //Notifications
        //     //Website logos
        //     //gallery


        // }
        // else  if($menu->id == 2)
        //     dd('About Us');
        // else  if($menu->id == 3)
        //     dd('Contact Us');
        // else
        //     dd('Others');



        // $gallery = Gallery::with('parentGallery')->get();

        // $getGallery = Gallery::getGallery();

        // return array($gallery, $getGallery);
    }
    public function create()
    {
        //
    }

    public function saveComponent(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'menu_id' => 'required|integer',
                'component_id' => 'required|integer',
                'component_name' => 'required|string',
            ]);

            $existingSection = PageSection::where('menu_id', $validatedData['menu_id'])
                ->where('page_section_id', $validatedData['component_id'])
                ->first();

            if ($existingSection) {
                return response()->json([
                    'error' => 'Duplicate Entry',
                    'message' => 'This component is already added to the menu.'
                ], 409);
            }


            DB::select("SELECT setval(pg_get_serial_sequence('page_section', 'id'), MAX(id)) FROM page_section");

            $pageSection = new PageSection();
            $pageSection->menu_id = $validatedData['menu_id'];
            $pageSection->page_section_name = $validatedData['component_name'];
            $pageSection->order = 1;
            $pageSection->status = 1;
            $pageSection->page_section_id = $validatedData['component_id'];

            $pageSection->save();



            return response()->json(['success' => true, 'data' => $pageSection], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteComponent(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'menu_id' => 'required|integer',
                'component_id' => 'required|integer',
                'component_name' => 'required|string',
            ]);

            $existingSection = PageSection::where('menu_id', $validatedData['menu_id'])
                ->where('id', $validatedData['component_id'])
                ->first();

            dd($validatedData['menu_id'], $validatedData['component_id'], $existingSection);

            if ($existingSection) {



                return response()->json(['success' => true, 'data' => $existingSection], 201);
            } else {
                return response()->json(['error' => 'An unexpected error occurred', 'message' => 'Component does not exist'], 500);
            }


        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function getAllComponents()
    {
        return PageSectionMaster::all();
    }

}
