<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $user = auth()->user();     
        $hasResult = $user->results()->exists();
        ds($hasResult);
        // ray()->showEvents();
        ray()->showCache();
        return Inertia::render('Dashboard', [
            'hasResult' => $hasResult
        ]);
    }
}
