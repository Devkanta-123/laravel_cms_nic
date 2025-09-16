<?php

namespace App\Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    public static function sanitize($html)
    {
        // Create default config
        $config = HTMLPurifier_Config::createDefault();

        // Allow only specific tags and attributes
        $config->set(
            'HTML.Allowed',
            'p,b,i,u,a[href],ul,ol,li,br,img[src|alt|title|width|height],h1,h2,h3,h4,h5,h6'
        );
        $config->set('AutoFormat.AutoParagraph', true);
        $config->set('AutoFormat.RemoveEmpty', true);

        // Create purifier instance
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
}
