<?php

namespace App\Helpers;

class ImageHelper
{
    public static function featuredImage($path)
    {
        if (!$path) {
            return null;
        }

        // Check if the path already starts with storage/
        if (strpos($path, 'storage/') === 0) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }
}
