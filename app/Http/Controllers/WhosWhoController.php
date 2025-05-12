<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhosWho;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WhosWhoController extends Controller
{
    //

    public function addWhosWho(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg', // Image validation
            'level_id' => 'required|exists:level_master,id'
        ]);

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

        // // Ensure either district_name or block_name has data
        // if (empty($request->district_name) && empty($request->block_name)) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Either district_name or block_name must be provided'
        //     ], 400);
        // }

        // Create a new WhosWho instance
        $whoswho = new WhosWho();
        $whoswho->name = $request->name;
        $whoswho->designation = $request->designation;
        $whoswho->email = $request->email;
        $whoswho->contact = $request->contact;
        $whoswho->level_id = $request->level_id;
        $whoswho->district_name = $request->district_name ?? null;
        $whoswho->block_name = $request->block_name ?? null;
        $whoswho->user_id = $user->id ?? null;
        $whoswho->flag = $flag ?? null;
        $whoswho->role_id = $user->role_id ?? null;

        // Handle image upload if a file is provided
        if ($request->hasFile('profile_image')) {
            $folderPath = 'public/whoswho';
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs($folderPath, $imageName);

            $whoswho->profile_image = $imagePath;
        }

        // Save the record to the database
        $whoswho->save();

        // Return a success response
        return response()->json(['status' => 'success', 'message' => 'Data added successfully']);
    }


  public function getWhosWho()
{
    $user = Auth::user();

    if (in_array($user->role_id, [3, 4])) {
        // Return joined data with addedby
        $whoswho = DB::table('whos_who as ww')
            ->join('users as u', 'u.id', '=', 'ww.user_id')
            ->select('ww.*', 'u.name as addedby')
            ->get();
    } else {
        // Return all records without join
        $whoswho = WhosWho::all();
    }

    return response()->json($whoswho);
}

}
