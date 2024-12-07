<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class MainTestController extends Controller
{
    public function index()
    {
        $currentStage = Session::get('current_test_stage', 'holland_codes');
        dd($currentStage);
        return Inertia::render('Test/MainTest', [
            'testStage' => $currentStage
        ]);
    }
}
