<?php

namespace App\Services;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

readonly class PageVisitTracker
{
    public function __construct(
        private GeoIPService $geoipService
    ) {}

    public function trackVisit(Request $request): void
    {
        if ($this->shouldSkipTracking($request)) {
            return;
        }

        $path = $request->path();
        $routeName = $request->route()?->getName();

        Cache::lock("page-visit-{$path}")->block(5, function () use ($path, $routeName, $request) {
            $visit = PageVisit::firstOrNew(['path' => $path]);
            
            // Get location data
            $location = $this->geoipService->getLocation($request->ip());
            
            // Update basic info
            $visit->route_name = $routeName;
            $visit->visit_count++;
            $visit->last_visit_at = now();
            $visit->user_id = auth()->id();

            // Add location data
            $visit->ip = $request->ip();
            $visit->country = $location['country'];
            $visit->city = $location['city'];
            $visit->latitude = $location['latitude'];
            $visit->longitude = $location['longitude'];
            $visit->timezone = $location['timezone'];
            $visit->currency = $location['currency'];

            // Add browser/device data
            $visit->browser_name = $request->userAgent();
            $visit->platform = $request->header('Sec-CH-UA-Platform');
            $visit->device = $this->detectDevice($request);

            // Add metadata
            $visit->metadata = [
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
                'accept_language' => $request->header('accept-language'),
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

    private function detectDevice(Request $request): string
    {
        $userAgent = strtolower($request->userAgent());
        
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
            return 'tablet';
        }
        
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $userAgent)) {
            return 'mobile';
        }
        
        return 'desktop';
    }
} 