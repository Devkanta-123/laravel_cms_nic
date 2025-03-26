<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhosWho;
use Illuminate\Support\Facades\Storage;
class WhosWhoController extends Controller
{
    //

    public function addWhosWho(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'nullable|string|unique:whos_who,email', // Make email nullable
            'contact' => 'nullable|string|max:20', // Make contact nullable
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg', // Image validation
            'level_id' => 'required|exists:level_master,id',
            'district_name' => 'nullable|string|max:255',
            'block_name' => 'nullable|string|max:255',
        ]);

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
        $whoswho = WhosWho::all();
        return response()->json($whoswho);
    }
}
