<?php

if (!function_exists('display_image')) {
    function display_image($path) {
        if (!$path) return 'https://via.placeholder.com/400x400?text=No+Image'; 
        
        // If it's already a full URL (likely Cloudinary)
        if (str_starts_with($path, 'http')) {
            // Add Cloudinary auto optimization if it's a cloudinary URL
            if (str_contains($path, 'cloudinary.com')) {
                // Insert f_auto,q_auto after /upload/
                return str_replace('/upload/', '/upload/f_auto,q_auto/', $path);
            }
            return $path;
        }
        
        return asset('storage/' . $path);
    }
}
