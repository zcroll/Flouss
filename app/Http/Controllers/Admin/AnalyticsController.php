<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService
    ) {}

    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(30)));
        $endDate = Carbon::parse($request->input('end_date', now()));

        $analytics = $this->analyticsService->getAnalyticsSummary($startDate, $endDate);

        return Inertia::render('Admin/Dashboard/Analytics/index', [
            'analytics' => $analytics,
            'dateRange' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'filters' => $request->only(['start_date', 'end_date', 'period']),
        ]);
    }

    public function updateTimeRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'period' => 'nullable|in:daily,weekly,monthly'
        ]);

        return redirect()->route('admin.analytics', [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'period' => $request->period
        ]);
    }
} 