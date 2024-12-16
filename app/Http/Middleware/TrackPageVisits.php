<?php

namespace App\Http\Middleware;

use App\Services\PageVisitTracker;
use Closure;
use Illuminate\Http\Request;

class TrackPageVisits
{
    public function __construct(
        private readonly PageVisitTracker $visitTracker
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('GET') && !$request->ajax()) {
            $this->visitTracker->trackVisit($request);
        }

        return $response;
    }
}   