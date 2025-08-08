<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    public function generate(Request $request)
    {
        $a = rand(1, 9);
        $b = rand(1, 9);
        $expression = "$a + $b";
        $answer = $a + $b;

        $key = Str::uuid()->toString();

        // Store in cache for 3 minutes
        Cache::put('captcha_' . $key, $answer, now()->addMinutes(3));

        // Generate image
        $image = imagecreate(120, 40);
        $bg = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        imagestring($image, 5, 30, 10, $expression, $textColor);

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response()->json([
            'captcha_img' => 'data:image/png;base64,' . base64_encode($imageData),
            'captcha_key' => $key,
            'captcha_text' => $expression, // ✅ Add this
        ]);
    }
}
