<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Map;


class MapController extends Controller
{
    //
    public function index() {}
    public function storeMapData(Request $request)
    {
        $request->validate([
            'iframe' => 'required|string'
        ]);

        // Check if a map record exists, then update, otherwise create
        $map = Map::first();
        $user = Auth::user(); // This will get the authenticated user
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //before submit check roleid and set flag value
        // Determine flag based on role_id
        if ($user->role_id == 2) { //if admin upload 
            $flag = 'A';
        } elseif ($user->role_id == 3) { //if contentcreator upload
            $flag = 'N';
        }
        if ($map) {
            $map->update([
                'iframe' => $request->iframe,
                'flag' => $flag
            ]);
        } else {
            $map = Map::create([
                'iframe' => $request->iframe,
                'flag' => $flag,
                'user_id' => $user->id,
                'role_id' => $user->role_id
            ]);
        }

        return response()->json([
            'message' => 'Map data saved successfully!',
            'data' => $map
        ], 200);
    }

 public function getMapData(Request $request)
{
    $user = Auth::user();
    $flag = $request->input('flag');

    // Step 1: If flag is 'A', return maps with flag A only — no join
    if ($flag === 'A') {
        $map = Map::where('flag', 'A')->get();
        return response()->json($map);
    }

    // Step 2: Else apply role-based logic
    if (in_array($user->role_id, [2,3,4])) {
        // Roles 3 or 4 with join to get addedby
        $map = DB::table('map as m')
            ->select('m.*', 'u.name as addedby')
            ->leftJoin('users as u', 'u.id', '=', 'm.user_id')
            ->get();
    } else {
        // Other roles without join
        $map = Map::all();
    }

    return response()->json($map);
}



    public function approveMap(Request  $request)
    {
        $request->validate([
            'id' => 'required|exists:map,id'
        ]);
        $map = Map::find($request->id);
        $map->flag = 'A'; // Approve
        $map->save();
        return response()->json(['success' => true, 'message' => 'Map approved successfully']);
    }
}
