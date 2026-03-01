<?php

if (!function_exists('display_image')) {
    function display_image($path) {
        if (!$path) return 'https://via.placeholder.com/400x400?text=No+Image'; 
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        return asset('storage/' . $path);
    }
}
