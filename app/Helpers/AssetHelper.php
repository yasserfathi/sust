<?php

if (!function_exists('versioned_asset')) {
    /**
     * Generate an asset path with a version cache-buster based on filemtime.
     *
     * @param string $path
     * @return string
     */
    function versioned_asset($path)
    {
        $fullPath = public_path($path);
        
        if (file_exists($fullPath)) {
            return asset($path) . '?v=' . filemtime($fullPath);
        }
        
        // Fallback to standard asset if the file doesn't exist to prevent warnings
        return asset($path);
    }
}
