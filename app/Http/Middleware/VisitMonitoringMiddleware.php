<?php

namespace Binafy\LaravelUserMonitoring\Middlewares;

use Binafy\LaravelUserMonitoring\Utills\Detector;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GeoIPService;

class VisitMonitoringMiddleware
{
    private GeoIPService $geoipService;

    public function __construct(GeoIPService $geoipService)
    {
        $this->geoipService = $geoipService;
    }

    /**
     * Handle monitor visiting.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (config('user-monitoring.visit_monitoring.turn_on', false) === false) {
            return $next($request);
        }
        if (config('user-monitoring.visit_monitoring.ajax_requests', false) === false && $request->ajax()) {
            return $next($request);
        }

        $detector = new Detector();
        $guard = config('user-monitoring.user.guard', 'web');
        $exceptPages = config('user-monitoring.visit_monitoring.except_pages', []);

        if (empty($exceptPages) || !$this->checkIsExceptPages($request->path(), $exceptPages)) {
            $ip = $request->ip();
            $location = $this->geoipService->getLocation($ip);

            DB::table(config('user-monitoring.visit_monitoring.table'))->insert([
                'user_id' => auth($guard)->id(),
                'browser_name' => $detector->getBrowser(),
                'platform' => $detector->getDevice(),
                'device' => $detector->getDevice(),
                'ip' => $ip,
                'page' => $request->url(),
                'country' => $location['country'],
                'city' => $location['city'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'timezone' => $location['timezone'],
                'currency' => $location['currency'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }

    /**
     * Check request page are exists in expect pages.
     */
    private function checkIsExceptPages(string $page, array $exceptPages): bool
    {
        return collect($exceptPages)->contains($page);
    }
}
