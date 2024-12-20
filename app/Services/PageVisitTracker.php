<?php

namespace App\Services;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class PageVisitTracker
{
    private const CACHE_TTL = 3600; // 1 hour
    private const BATCH_SIZE = 100;
    private const LOCK_TIMEOUT = 5;

    public function __construct(
        private GeoIPService $geoipService
    ) {}

    public function trackVisit(Request $request): void
    {
        if ($this->shouldSkipTracking($request)) {
            return;
        }

        try {
            $this->processVisit($request);
        } catch (Throwable $e) {
            Log::error('Failed to track page visit', [
                'error' => $e->getMessage(),
                'path' => $request->path(),
                'ip' => $request->ip()
            ]);
        }
    }

    private function processVisit(Request $request): void
    {
        $path = $request->path();
        $routeName = $request->route()?->getName();
        $cacheKey = "page-visit:{$path}";
        
        // Use Redis for atomic increment of visit count
        $visitCount = Redis::incr("visit-count:{$path}");
        
        // Process in batches
        if ($visitCount % self::BATCH_SIZE === 0) {
            $this->processBatchUpdate($path, $request, $routeName);
            return;
        }

        // Queue the visit data for batch processing
        $this->queueVisitData($path, $request, $routeName);
    }

    private function processBatchUpdate(string $path, Request $request, ?string $routeName): void
    {
        Cache::lock("batch-update:{$path}", self::LOCK_TIMEOUT)->block(
            self::LOCK_TIMEOUT,
            function () use ($path, $request, $routeName) {
                $visit = PageVisit::firstOrNew(['path' => $path]);
                
                // Get cached location data or fetch new
                $location = $this->getCachedLocation($request->ip());
                
                // Update basic info
                $visit->route_name = $routeName;
                $visit->visit_count = (int) Redis::get("visit-count:{$path}");
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

                // Clear queued data after successful batch update
                Redis::del("queued-visits:{$path}");
            }
        );
    }

    private function queueVisitData(string $path, Request $request, ?string $routeName): void
    {
        $visitData = [
            'ip' => $request->ip(),
            'user_id' => auth()->id(),
            'user_agent' => $request->userAgent(),
            'platform' => $request->header('Sec-CH-UA-Platform'),
            'referrer' => $request->header('referer'),
            'accept_language' => $request->header('accept-language'),
            'route_name' => $routeName,
            'timestamp' => now()->toIso8601String()
        ];

        Redis::rpush("queued-visits:{$path}", json_encode($visitData));
    }

    private function getCachedLocation(string $ip): array
    {
        $cacheKey = "geoip:{$ip}";
        
        return Cache::remember(
            $cacheKey,
            now()->addDay(),
            fn() => $this->geoipService->getLocation($ip)
        );
    }

    private function shouldSkipTracking(Request $request): bool
    {
        return $request->is('admin/*') || 
               $request->is('api/*') || 
               $request->is('_debugbar/*') ||
               $request->is('assets/*') ||
               $request->is('favicon.ico');
    }

    private function detectDevice(Request $request): string
    {
        $userAgent = strtolower($request->userAgent());
        
        return Cache::remember(
            "device-type:{$userAgent}",
            now()->addWeek(),
            function () use ($userAgent) {
                if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
                    return 'tablet';
                }
                
                if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $userAgent)) {
                    return 'mobile';
                }
                
                return 'desktop';
            }
        );
    }
} 