<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PageVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $timeRange = request()->input('timeRange', 'last7days');
        
        // Get date range based on selection
        $dates = $this->getDateRange($timeRange);
        
        // Get analytics data
        $analyticsData = [
            'visits' => $this->getVisitsAnalytics($dates),
            'topPages' => $this->getTopPages($dates),
            'userActivity' => $this->getUserActivity($dates),
            'overview' => $this->getOverviewStats($dates),
        ];

        return Inertia::render('Admin/Dashboard/Analytics/index', [
            'analytics' => $analyticsData,
            'dateRange' => [
                'start' => $dates['start']->format('Y-m-d'),
                'end' => $dates['end']->format('Y-m-d'),
            ],
        ]);
    }

    private function getDateRange($timeRange)
    {
        $end = Carbon::now();
        
        $start = match($timeRange) {
            'today' => Carbon::today(),
            'yesterday' => Carbon::yesterday(),
            'last7days' => Carbon::now()->subDays(7),
            'last30days' => Carbon::now()->subDays(30),
            'thisMonth' => Carbon::now()->startOfMonth(),
            'lastMonth' => Carbon::now()->subMonth()->startOfMonth(),
            default => Carbon::now()->subDays(7),
        };

        return ['start' => $start, 'end' => $end];
    }

    private function getVisitsAnalytics($dates)
    {
        return PageVisit::withinDates($dates['start'], $dates['end'])
            ->select(DB::raw('DATE(last_visit_at) as date'), DB::raw('SUM(visit_count) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($item) => [
                'date' => $item->date,
                'count' => $item->count
            ]);
    }

    private function getTopPages($dates)
    {
        return PageVisit::withinDates($dates['start'], $dates['end'])
            ->mostVisited()
            ->limit(10)
            ->get()
            ->map(fn($visit) => [
                'url' => $visit->formatted_path,
                'name' => $visit->display_name,
                'visits' => $visit->visit_count,
                'last_visit' => $visit->last_visit_at->diffForHumans(),
            ]);
    }

    private function getUserActivity($dates)
    {
        return PageVisit::withinDates($dates['start'], $dates['end'])
            ->recent()
            ->get()
            ->map(fn($visit) => [
                'url' => $visit->formatted_path,
                'method' => 'GET',
                'ip' => $visit->metadata['ip'] ?? 'unknown',
                'created_at' => $visit->last_visit_at->diffForHumans(),
            ]);
    }

    private function getOverviewStats($dates)
    {
        $totalVisits = PageVisit::withinDates($dates['start'], $dates['end'])->sum('visit_count');
        $previousPeriodStart = Carbon::parse($dates['start'])->subDays(Carbon::parse($dates['start'])->diffInDays($dates['end']));
        $previousPeriodVisits = PageVisit::withinDates($previousPeriodStart, $dates['start'])->sum('visit_count');
        
        $percentChange = $previousPeriodVisits > 0 
            ? (($totalVisits - $previousPeriodVisits) / $previousPeriodVisits) * 100 
            : 100;

        return [
            'total_visits' => $totalVisits,
            'unique_pages' => PageVisit::withinDates($dates['start'], $dates['end'])->count(),
            'percent_change' => round($percentChange, 1),
            'average_daily_visits' => round($totalVisits / max(1, Carbon::parse($dates['start'])->diffInDays($dates['end'])))
        ];
    }

    public function updateTimeRange(Request $request)
    {
        $request->validate([
            'timeRange' => 'required|string|in:today,yesterday,last7days,last30days,thisMonth,lastMonth',
        ]);

        $dates = $this->getDateRange($request->timeRange);
        
        return response()->json([
            'analytics' => [
                'visits' => $this->getVisitsAnalytics($dates),
                'topPages' => $this->getTopPages($dates),
                'userActivity' => $this->getUserActivity($dates),
                'overview' => $this->getOverviewStats($dates),
            ],
            'dateRange' => [
                'start' => $dates['start']->format('Y-m-d'),
                'end' => $dates['end']->format('Y-m-d'),
            ],
        ]);
    }
} 