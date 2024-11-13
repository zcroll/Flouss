<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class StyleController extends Controller
{
    public function getStyles()
    {
        // Cache for 24 hours (or adjust as needed)
        return Cache::remember('external-styles', 60 * 24, function () {
            // Replace with your style file URL
            $response = Http::get('https://your-style-url.com/styles.css');
            return $response->body();
        });
    }
}
