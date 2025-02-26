<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageSectionMaster;

class PageSectionMasterController extends Controller
{
    //
    public function store_page_section_master(Request $request)
    {
        $request->validate([
            'file' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048', // Image validation
        ]);

        // Check if the page section already exists
        $existingRecord = PageSectionMaster::where('page_section_name', $request->pagesectionname)->exists();
        if ($existingRecord) {
            return response()->json([
                'message' => 'same page section  name already exists.'
            ], 409);
        }

        // Handle file upload if exists
        $filePath = null;
        if ($request->hasFile('logo')) {
            $filePath = $request->file('logo')->store('logo', 'public'); // Save in storage
        }

        // Create new PageSectionMaster entry
        $pageSectionMaster = new PageSectionMaster();
        $pageSectionMaster->page_section_name = $request->pagesectionname;
        $pageSectionMaster->icon = $filePath;
        $pageSectionMaster->save();

        return response()->json(['message' => 'Page Section Master created successfully'], 201);
    }


    public function get_page_section_master()
    {
        $pageSectionMaster = PageSectionMaster::all();
        return response()->json($pageSectionMaster);
    }
    public function update_page_section_master(Request $request, $id)
    {
        // Validate input data
        $request->validate([
            'pagesectionname' => 'required|string|max:255',
            'logo' => 'nullable|mimes:png,jpg,jpeg,svg|max:2048', // Validate logo file type & size
        ]);

        // Find the existing record
        $pageSection = PageSectionMaster::find($id);

        if (!$pageSection) {
            return response()->json(['message' => 'Page Section not found'], 404);
        }

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($pageSection->icon && \Storage::disk('public')->exists($pageSection->icon)) {
                \Storage::disk('public')->delete($pageSection->icon);
            }

            // Store new logo in 'storage/app/public/logo'
            $logoPath = $request->file('logo')->store('logo', 'public');

            // Update logo path in DB
            $pageSection->icon = $logoPath;
        }

        // Update other fields
        $pageSection->page_section_name = $request->pagesectionname;
        $pageSection->save();

        return response()->json(['message' => 'Page Section updated successfully'], 200);
    }

}
