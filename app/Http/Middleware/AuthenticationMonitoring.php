<?php

namespace App\Http\Middleware;

use App\Models\AuthenticationLog;
use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class AuthenticationMonitoring
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (config('monitoring.authentication.enabled', true)) {
            if ($request->is('login') && $request->isMethod('post')) {
                $this->logAuthentication($request);
            }
            
            if ($request->is('logout') && auth()->check()) {
                $this->logLogout($request);
            }
        }

        return $response;
    }

    private function logAuthentication(Request $request): void
    {
        $agent = new Agent();
        
        AuthenticationLog::create([
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'login_successful' => auth()->check(),
            'device_type' => $agent->device(),
            'browser' => $agent->browser()
        ]);
    }

    private function logLogout(Request $request): void
    {
        $latestLog = AuthenticationLog::where('user_id', auth()->id())
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first();

        if ($latestLog) {
            $latestLog->update([
                'logout_at' => now()
            ]);
        }
    }
} 