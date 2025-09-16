<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Map;
use App\Models\AppTrack;
use App\Models\User;

class MapController extends Controller
{
    //
    public function index() {}
    public function storeMapData(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'zoom' => 'nullable|integer|min:1|max:20',
            'place_name' => 'nullable|string',
            'publisher_id' => 'nullable|integer',
            'menu_id' => 'required',
            'page_section_master_id' => 'required'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $publisher = User::find($request->publisher_id);

        // Determine flag based on role_id
        $flag = ($user->role_id == 2) ? 'A' : 'N';

        $map = Map::first(); // get the first record

        if ($map) {
            // Update existing record
            $map->update([
                'lat'         => $request->lat,
                'lng'         => $request->lng,
                'zoom'        => $request->zoom ?? 14,
                'place_name'  => $request->place_name,
                'flag'        => 'U',
                'publisher_id' => $request->publisher_id ?? $map->publisher_id
            ]);

            $userTo = User::find($map->user_id);

            AppTrack::create([
                'menu_id'                 => $request->menu_id,
                'page_section_master_id'  => $request->page_section_master_id,
                'user_from'               => $user->id,
                'user_from_name'          => $user->name,
                'user_to'                 => $map->user_id,
                'user_to_name'            => $userTo ? $userTo->name : null,
                'remarks'                 => 'Map updated: ' . $map->lat . ',' . $map->lng,
                'action'                  => 'Updated',
                'flag'                    => 'U',
                'application_id'          => $map->application_id
            ]);
        } else {
            // Create new record if none exists
            $applicationId = 'MAP' . now()->format('YmdHis');

            $map = Map::create([
                'lat'          => $request->lat,
                'lng'          => $request->lng,
                'zoom'         => $request->zoom ?? 14,
                'place_name'   => $request->place_name,
                'flag'         => $flag,
                'user_id'      => $user->id,
                'application_id' => $applicationId,
                'publisher_id' => $request->publisher_id ?? null
            ]);

            AppTrack::create([
                'application_id'          => $applicationId,
                'menu_id'                 => $request->menu_id,
                'page_section_master_id'  => $request->page_section_master_id,
                'user_from'               => $user->id,
                'user_from_name'          => $user->name,
                'user_to'                 => $request->publisher_id,
                'user_to_name'            => $publisher ? $publisher->name : null,
                'remarks'                 => 'Map created: ' . $request->lat . ',' . $request->lng,
                'flag'                    => $flag,
                'action'                  => 'Add',
            ]);
        }

        return response()->json([
            'message' => $map ? 'Map data saved successfully!' : 'Error saving map data',
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
        if (in_array($user->role_id, [2, 3, 4])) {
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



    public function approveMap(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:map,id'
        ]);
        $map = Map::find($request->id);
        $map->flag = 'A'; // Approve
        $map->save();
        $user = Auth::user();
        $userTo = User::find($map->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $map->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Map approved: ' . $map->iframe,
            'action' => 'Approved',
            'flag' => 'A',
            'application_id' => $map->application_id
        ]);
        return response()->json(['success' => true, 'message' => 'Map approved successfully']);
    }
    public function rejectedMap(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'remarks' => 'required|string',
        ]);
        $map = Map::find($request->id);
        if (!$map) {
            return response()->json([
                'success' => false,
                'message' => 'map data not found',
            ], 404);
        }
        $map->flag = 'R';
        $map->rejected_remarks = $request->remarks;
        $map->save();
        $user = Auth::user();
        $userTo = User::find($map->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $map->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Map Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $map->application_id
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Map  has been rejected',
        ]);
    }

    public function deleteMap(Request $request)
    {
        $id = $request->input('id');
        $map = Map::find($id);
        if (!$map) {
            return response()->json(['message' => 'map not found'], 404);
        }

        // Delete the record
        $map->delete();
        $user = Auth::user();
        $userTo = User::find($map->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $map->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'Map Deleted: ' . $map->iframe,
            'action' => 'Deleted',
            'flag' => 'D',
            'application_id' => $map->application_id
        ]);
        return response()->json(['message' => 'Map has been deleted']);
    }
}
