<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class WelcomeBackController extends Controller
{
    public function showWelcomeBack()
    {
        $showWelcomeBack = Session::get('show_welcome_back', false);
        
        return response()->json([
            'showWelcomeBack' => $showWelcomeBack
        ]);
    }
    
    public function setWelcomeBackShown()
    {
        Session::put('show_welcome_back', false);
        
        return response()->json([
            'success' => true
        ]);
    }
}
