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
}
