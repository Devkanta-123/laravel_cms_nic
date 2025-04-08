<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Facades\URL;
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

        if ($map) {
            $map->update([
                'iframe' => $request->iframe
            ]);
        } else {
            $map = Map::create([
                'iframe' => $request->iframe
            ]);
        }

        return response()->json([
            'message' => 'Map data saved successfully!',
            'data' => $map
        ], 200);
    }

    public function getMapData()
    {
        $map = Map::all();
        return response()->json($map);
    }
}
