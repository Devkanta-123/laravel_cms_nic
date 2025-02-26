<?php

namespace App\Helpers;

use App\Models\Theme;

class ThemeHelper
{
    public static function getFolderPath()
    {
        // $parsedUrl = parse_url(url()->current(), PHP_URL_PATH); 
        // $segments = explode('/', trim($parsedUrl, '/')); 
        // $folder = end($segments);

        $theme = Theme::with('theme_master')->get();

        return $theme ? $theme[0]->theme_master['path'] : null;
    }
}
