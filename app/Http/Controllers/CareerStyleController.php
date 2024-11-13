<?php

namespace App\Http\Controllers;

use App\Services\CareerStyleService;
use Illuminate\Http\Response;

class CareerStyleController extends Controller
{
    protected $styleService;

    public function __construct(CareerStyleService $styleService)
    {
        $this->styleService = $styleService;
    }

    public function show($path)
    {
        $style = $this->styleService->getStyle($path);
        
        if (!$style) {
            abort(404);
        }

        return response($style)
            ->header('Content-Type', 'text/css')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
