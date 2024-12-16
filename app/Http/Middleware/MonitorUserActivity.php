<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class MonitorUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldMonitorRequest($request)) {
            $this->recordVisit($request);
        }

        return $next($request);
    }

    private function shouldMonitorRequest(Request $request): bool
    {
        return !$request->ajax() 
            && !$this->isExcludedPath($request->path())
            && config('monitoring.enabled', true);
    }

    private function isExcludedPath(string $path): bool
    {
        return in_array($path, config('monitoring.excluded_paths', []));
    }

    private function recordVisit(Request $request): void
    {
        $agent = new Agent();
        
        $ipAddress = $this->getRealIpAddress($request);

        PageVisit::create([
            'user_id' => auth()->id(),
            'path' => $request->path(),
            'full_url' => $request->fullUrl(),
            'method' => $request->method(),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'platform' => $agent->platform(),
            'device' => $agent->device(),
            'ip_address' => $ipAddress,
            'visit_time' => now(),
            'referrer' => $request->header('referer'),
            'user_agent' => $request->userAgent()
        ]);
    }

    private function getRealIpAddress(Request $request): string 
    {
        if (config('monitoring.use_reverse_proxy_ip', false)) {
            $header = config('monitoring.real_ip_header', 'X-Forwarded-For');
            return $request->header($header) ?? $request->ip();
        }
        
        return $request->ip();
    }
} 