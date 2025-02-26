<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slides;

class SectionsController extends Controller
{

    public function fetchSliderImages()
    {
        $slides = Slides::all(); 
       
        return response()->json($slides);
    }

    public function uploadSliderImages(Request $request)
    {
        $files = $request->allFiles();

        foreach($files as $file){
            $file_name = $file->getClientOriginalName();
            $path = $file->storeAs("slides", $file_name);
            $slide = new Slides();
            $slide->path = $path;
            $slide->save();
        }

        return response()->json(['message' => 'Images uploaded successfully'], 200);
    }
}
