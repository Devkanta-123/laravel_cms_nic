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
        $config->set('HTML.Allowed', 'p,b,i,u,a[href],ul,ol,li,br');
        $config->set('AutoFormat.AutoParagraph', true);
        $config->set('AutoFormat.RemoveEmpty', true);

        // Create purifier instance
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
}
