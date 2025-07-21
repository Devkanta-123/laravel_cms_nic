<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelMaster;

class LevelMasterController extends Controller
{
    //
    public function addLevelMaster(Request $request)
    {
        $level = new LevelMaster();
        $level->level_name = $request->level_name;
        $level->save();
        return response()->json(['status' => 'success', 'message' => 'Level added successfully']);
    }
    public function getAllLevelMaster(){
        $levels = LevelMaster::all();
        return response()->json($levels);
    }

     public function editLevelMaster(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'editlevel_name' => 'required|string|max:255',
        ]);

        // Step 1: Find the levelMaster
        $levelMaster = LevelMaster::find($request->id);

        if (!$levelMaster) {
            return response()->json(['message' => 'Level Master found'], 404);
        }
  
        $levelMaster->level_name = $request->editlevel_name;
        $levelMaster->save();
        return response()->json(['message' => 'Level Master Data  updated successfully'], 201);
    }

    public function deleteLevelMaster(Request $request)
    {
        // Step 1: Validate input
        $request->validate([
            'id' => 'required|integer|exists:level_master,id',
        ]);

        // Step 2: Find the category
        $levelMaster = LevelMaster::find($request->id);

        if (!$levelMaster) {
            return response()->json(['message' => 'Level not found'], 404);
        }

        // Step 3: Delete the category
        $levelMaster->delete();

        // Step 4: Return success response
        return response()->json(['message' => 'Level Master deleted successfully'], 200);
    }
    
}
