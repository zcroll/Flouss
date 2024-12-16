<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PageVisitTracker;

class TrackPageVisits
{
    protected PageVisitTracker $visitTracker;

    public function __construct(PageVisitTracker $visitTracker)
    {
        $this->visitTracker = $visitTracker;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if ($request->method() === 'GET') {
            $this->visitTracker->trackVisit($request);
        }

        return $response;
    }
}   