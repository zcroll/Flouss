<?php

namespace App\Http\Controllers\sticky;

use Illuminate\Http\Request;
use Inertia\Inertia;

class StickycartController
{
    public function index(Request $request)
    {
        $baseUri = $request->url();

        return Inertia::render('StickyCart', [
            'baseUri' => $baseUri,
        ]);
    }
}
