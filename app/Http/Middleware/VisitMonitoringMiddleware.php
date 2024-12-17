<?php

namespace App\Http\Middleware;

use App\Services\Contracts\GeoLocationService;
use Binafy\LaravelUserMonitoring\Utills\Detector;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VisitMonitoringMiddleware
{
    public function __construct(
        private readonly GeoLocationService $geoLocationService
    ) {
        Log::info('VisitMonitoringMiddleware initialized');
    }

    /**
     * Handle monitor visiting.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            Log::info('Starting visit monitoring process');

            if (!$this->shouldMonitorRequest($request)) {
                Log::info('Request should not be monitored', [
                    'path' => $request->path(),
                    'ajax' => $request->ajax()
                ]);
                return $next($request);
            }

            $ip = $request->ip();
            Log::info('Processing request IP', ['ip' => $ip]);

            // Get location data
            $locationData = $this->geoLocationService->getLocation($ip);
            Log::info('Location data received', ['location' => $locationData]);

            // Save visit data
            $this->saveVisitData($request, $ip, $locationData);
            Log::info('Visit data saved successfully');

        } catch (Exception $e) {
            Log::error('Visit monitoring failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $next($request);
    }

    private function shouldMonitorRequest(Request $request): bool
    {
        $monitoring = config('user-monitoring.visit_monitoring.turn_on', false);
        $ajaxAllowed = config('user-monitoring.visit_monitoring.ajax_requests', false);
        $exceptPages = config('user-monitoring.visit_monitoring.except_pages', []);

        Log::info('Checking if request should be monitored', [
            'monitoring_enabled' => $monitoring,
            'ajax_allowed' => $ajaxAllowed,
            'is_ajax' => $request->ajax(),
            'path' => $request->path(),
            'except_pages' => $exceptPages
        ]);

        if (!$monitoring) {
            Log::info('Monitoring is disabled');
            return false;
        }

        if (!$ajaxAllowed && $request->ajax()) {
            Log::info('Ajax request not allowed');
            return false;
        }

        if (!empty($exceptPages) && in_array($request->path(), $exceptPages, true)) {
            Log::info('Page is in except list');
            return false;
        }

        Log::info('Request should be monitored');
        return true;
    }

    private function saveVisitData(Request $request, string $ip, array $location): void
    {
        $detector = new Detector();
        $guard = config('user-monitoring.user.guard', 'web');

        $visitData = [
            'user_id' => auth($guard)->id(),
            'browser_name' => $detector->getBrowser(),
            'platform' => $detector->getDevice(),
            'device' => $detector->getDevice(),
            'ip' => $ip,
            'page' => $request->url(),
            'country' => $location['country'] ?? 'Morocco',
            'city' => $location['city'] ?? 'Marrakesh',
            'latitude' => $location['latitude'] ?? 31.62252,
            'longitude' => $location['longitude'] ?? -7.98983,
            'timezone' => $location['timezone'] ?? 'Africa/Casablanca',
            'currency' => $location['currency'] ?? 'MAD',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Log::info('Preparing to save visit data', ['data' => $visitData]);

        try {
            DB::table(config('user-monitoring.visit_monitoring.table'))->insert($visitData);
            Log::info('Visit data saved successfully');
        } catch (Exception $e) {
            Log::error('Failed to save visit data', [
                'error' => $e->getMessage(),
                'data' => $visitData
            ]);
            throw $e;
        }
    }
}
