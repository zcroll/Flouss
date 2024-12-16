<?php

namespace App\Services;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageVisitTracker
{
    public function trackVisit(Request $request): void
    {
        if ($this->shouldSkipTracking($request)) {
            return;
        }

        $path = $request->path();
        $routeName = $request->route()?->getName();

        Cache::lock("page-visit-{$path}")->block(5, function () use ($path, $routeName, $request) {
            $visit = PageVisit::firstOrNew(['path' => $path]);
            
            $visit->route_name = $routeName;
            $visit->visit_count++;
            $visit->last_visit_at = now();
            $visit->metadata = [
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'referrer' => $request->header('referer'),
            ];
            
            $visit->save();
        });
    }

    private function shouldSkipTracking(Request $request): bool
    {
        return $request->is('admin/*') || 
               $request->is('api/*') || 
               $request->is('_debugbar/*');
    }
} 