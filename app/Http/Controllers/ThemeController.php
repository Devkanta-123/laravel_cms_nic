<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThemeMaster;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Theme;

class ThemeController extends Controller
{
    public function getThemes()
    {
        $themes = ThemeMaster::all();
    
        foreach ($themes as $theme) {
            $theme->image = Storage::url('theme/' . $theme->image);
        }

        $currentTheme = Theme::first();
        return response()->json([
            'themes' => $themes,
            'currentThemeId' => $currentTheme ? $currentTheme->theme : null,
        ]);
    }

  

    public function saveTheme(Request $request){

        $validator = Validator::make($request->all(), [
            'theme_id' => 'required',
          ]);
      
          if($validator->fails()){
            return response()->json("VE")->withHeaders([
              'Cache-Control' => 'max-age=15, public',
              'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
              'Vary' => 'Accept-Encoding',
            ]);
          }
          else {
            try{
                DB::beginTransaction();
                $theme = Theme::first();
           
                $theme->update([
                        'theme' => $this->normalizeString($request->theme_id),
                    ]);
                DB::commit();
                return response()->json(['message' => 'Theme updated successfully!'], 200);
            }
            catch(\Exception $e){
                DB::rollback();
                return response()->json(['message' => 'Error saving theme', 'error' => $e->getMessage()], 500);
            }
          }
    }
    
    public function normalizeString($str){
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = mb_ereg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace('%', '-', $str);
       return $str;
    }
}
