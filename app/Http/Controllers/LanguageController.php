<?php

namespace App\Http\Controllers;

use App\Enum\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function __invoke(Request $request)
    {



        session()->put('language', Lang::tryFrom($request->language)?->value ?? config('app.locale'));


        return back();

    }
}
   