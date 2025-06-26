<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhosWho;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AppTrack;
use App\Models\User;
class WhosWhoController extends Controller
{
    //

    public function addWhosWho(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|exists:level_master,id'
        ]);
        $publisher = User::find($request->publisher_id);
        $applicationId = 'WHOS' . now()->format('YmdHis');
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
        // Create a new WhosWho instance
        $whoswho = new WhosWho();
        $whoswho->application_id = $applicationId;
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
        $whoswho->publisher_id = $request->publisher_id ?? null;

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
        AppTrack::create([
            'application_id' => $applicationId,
            'menu_id' => $request->menu_id,
            'page_section_master_id' => $request->page_section_master_id,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $request->publisher_id,
            'user_to_name' => $publisher ? $publisher->name : null,
            'remarks' => 'WhosWho submitted: ' . $request->name,
            'flag' => $flag,
            'action' => 'Add',
        ]);
        // Return a success response
        return response()->json(['status' => 'success', 'message' => 'Data added successfully']);
    }


    public function updateWhosWho(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:whos_who,id',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'level_id' => 'required|exists:level_master,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $whoswho = WhosWho::find($request->id);
        $whoswho->name = $request->name;
        $whoswho->designation = $request->designation;
        $whoswho->email = $request->email;
        $whoswho->contact = $request->contact;
        $whoswho->level_id = $request->level_id;
        $whoswho->flag = 'U';

        // ✅ Handle profile image update
        if ($request->hasFile('profile_image')) {
            $folderPath = 'public/whoswho';

            // Ensure the folder exists
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            // ✅ Remove old image if exists
            if ($whoswho->profile_image && Storage::exists($whoswho->profile_image)) {
                Storage::delete($whoswho->profile_image);
            }

            // ✅ Store new image
            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs($folderPath, $imageName);
            $whoswho->profile_image = $imagePath;
        }

        $whoswho->save();

        $user = Auth::user();
        $userTo = User::find($whoswho->user_id);

        AppTrack::create([
            'menu_id' => $request->menu_id,
            'page_section_master_id' => $request->page_section_master_id,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $whoswho->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'WhosWHo updated: ' . $whoswho->name,
            'action' => 'Updated',
            'flag' => 'U',
            'application_id' => $whoswho->application_id
        ]);

        return response()->json(['status' => 'success', 'message' => 'WhosWho data updated successfully']);
    }


    public function getWhosWho(Request $request)
    {
        $user = Auth::user();
        $flag = $request->input('flag');

        if ($flag === 'A') {
            $whoswho = WhosWho::where('flag', 'A')->get();
            return response()->json($whoswho);
        }

        if (in_array($user->role_id, [2, 3, 4])) {
            // Return joined data with addedby and level_name
            $query = DB::table('whos_who as ww')
                ->join('users as u', 'u.id', '=', 'ww.user_id')
                ->leftJoin('level_master as lm', 'lm.id', '=', 'ww.level_id')
                ->select('ww.*', 'u.name as addedby', 'lm.level_name');

            $whoswho = $query->get();
            return response()->json($whoswho);
        } else {
            // Return records without join
            $query = WhosWho::leftJoin('level_master as lm', 'lm.id', '=', 'whos_who.level_id')
                ->select('whos_who.*', 'lm.level_name');

            if ($flag === 'A') {
                $query->where('whos_who.flag', 'A');
            }

            $whoswho = $query->get();
            return response()->json($whoswho);
        }

    }

    public function approveWhosWho(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:whos_who,id'
        ]);
        $whoswho = WhosWho::find($request->id);
        $whoswho->flag = 'A'; // Approve
        $whoswho->save();
        // Get current user (approver)
        $user = Auth::user();
        $userTo = User::find($whoswho->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $whoswho->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'WhosWHo approved: ' . $whoswho->name,
            'action' => 'Approved',
            'flag' => 'A',
            'application_id' => $whoswho->application_id
        ]);
        return response()->json(['success' => true, 'message' => 'WhosWho approved successfully']);
    }
    public function rejectedWhosWho(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'remarks' => 'required|string',
        ]);
        $whoswho = WhosWho::find($request->id);
        if (!$whoswho) {
            return response()->json([
                'success' => false,
                'message' => 'whoswho data not found',
            ], 404);
        }
        $whoswho->flag = 'R';
        $whoswho->rejected_remarks = $request->remarks;
        $whoswho->save();
        $user = Auth::user();
        $userTo = User::find($whoswho->user_id); // Get user_to from users table
        // Log into AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $whoswho->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'WhosWho Rejected: ' . $request->remarks,
            'action' => 'Rejected',
            'flag' => 'R',
            'application_id' => $whoswho->application_id
        ]);
        return response()->json([
            'success' => true,
            'message' => 'whoswho  has been rejected',
        ]);

    }

    public function deleteWhosWho(Request $request)
    {
        $id = $request->input('id');
        $whoswho = WhosWho::find($id);
        if (!$whoswho) {
            return response()->json(['message' => 'whoswho not found'], 404);
        }
        // Delete the file if it exists
        if ($whoswho->profile_image && Storage::exists($whoswho->profile_image)) {
            Storage::delete($whoswho->profile_image);
        }
        // Delete the record
        $whoswho->delete();
        $user = Auth::user();
        $userTo = User::find($whoswho->user_id);
        // Add entry to AppTrack
        AppTrack::create([
            'menu_id' => $request->menu_id ?? null,
            'page_section_master_id' => $request->page_section_master_id ?? null,
            'user_from' => $user->id,
            'user_from_name' => $user->name,
            'user_to' => $whoswho->user_id,
            'user_to_name' => $userTo ? $userTo->name : null,
            'remarks' => 'whoswho Deleted: ' . $whoswho->name,
            'action' => 'Deleted',
            'flag' => 'D',
            'application_id' => $whoswho->application_id

        ]);

        return response()->json(['message' => 'whoswho deleted successfully']);
    }
}
