<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryMaster;

class CategoryMasterController extends Controller
{
    //
    public function addCategoryMaster(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        $existingRecord = CategoryMaster::where('category_name', $request->category_name)->exists();
        if ($existingRecord) {
            return response()->json([
                'message' => 'same category name  already exists.'
            ], 409);
        }

        $new_category = CategoryMaster::create([
            'category_name' => $request->category_name,
        ]);


        return response()->json(['message' => 'Category Master created successfully'], 201);
    }

    public function getAllCategoryMaster()
    {
        $categories = CategoryMaster::all();
        return response()->json($categories);
    }
    public function editCategoryMaster(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $category = CategoryMaster::find($request->id);
        $category->category_name = $request->editcategory_name;
        $category->save();
        return response()->json(['message' => 'Category Master updated successfully'], 201);
    }
    

}
