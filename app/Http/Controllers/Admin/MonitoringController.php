<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthenticationLog;
use App\Models\PageVisit;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class MonitoringController extends Controller
{
    public function authentication(Request $request): Response
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subWeek()));
        $endDate = Carbon::parse($request->input('end_date', now()));

        $logs = AuthenticationLog::with('user')
            ->withinPeriod($startDate, $endDate)
            ->latest('login_at')
            ->paginate(15);

        return Inertia::render('Admin/Monitoring/AuthenticationLogs', [
            'analytics' => [
                'authentication_logs' => $logs,
                'summary' => [
                    'total_logins' => AuthenticationLog::successful()
                        ->withinPeriod($startDate, $endDate)
                        ->count(),
                    'failed_attempts' => AuthenticationLog::failed()
                        ->withinPeriod($startDate, $endDate)
                        ->count(),
                    'active_sessions' => AuthenticationLog::whereNull('logout_at')
                        ->where('login_successful', true)
                        ->count()
                ]
            ],
            'dateRange' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString()
            ]
        ]);
    }
} 