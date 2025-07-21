<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryMaster;
use App\Models\Menu;

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
            'editcategory_name' => 'required|string|max:255',
        ]);

        // Step 1: Find the category
        $category = CategoryMaster::find($request->id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Store the old name before updating
        $oldName = $category->category_name;

        // Step 2: Update the category name
        $category->category_name = $request->editcategory_name;
        $category->save();

        // Step 3: Update menu_name in Menu table where it matches old category name
        Menu::where('menu_name', $oldName)
            ->update(['menu_name' => $request->editcategory_name]);

        return response()->json(['message' => 'Category and matching Menu names updated successfully'], 201);
    }

    public function deleteCategoryMaster(Request $request)
    {
        // Step 1: Validate input
        $request->validate([
            'id' => 'required|integer|exists:category_master,id',
        ]);

        // Step 2: Find the category
        $category = CategoryMaster::find($request->id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Step 3: Delete the category
        $category->delete();

        // Step 4: Return success response
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }



}
