<?php

namespace App\Services;

use App\Models\PageVisit;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

readonly class AnalyticsService
{
    public function getAnalyticsSummary(Carbon $startDate, Carbon $endDate): array
    {
        $cacheKey = "analytics-summary-{$startDate->format('Y-m-d')}-{$endDate->format('Y-m-d')}";
        
        return Cache::remember(
            $cacheKey,
            now()->addHours(1),
            fn () => [
                'total_visits' => $this->getTotalVisits($startDate, $endDate),
                'most_visited_pages' => $this->getMostVisitedPages($startDate, $endDate),
                'visit_trends' => $this->getVisitTrends($startDate, $endDate),
                'time_analysis' => $this->getTimeAnalysis($startDate, $endDate),
                'location_analysis' => $this->getLocationAnalytics($startDate, $endDate),
            ]
        );
    }

    private function getTotalVisits(Carbon $startDate, Carbon $endDate): int
    {
        return PageVisit::withinPeriod($startDate, $endDate)
            ->sum('visit_count');
    }

    private function getMostVisitedPages(Carbon $startDate, Carbon $endDate, int $limit = 10): array
    {
        return PageVisit::withinPeriod($startDate, $endDate)
            ->topPages($limit)
            ->get(['path', 'visit_count', 'last_visit_at'])
            ->toArray();
    }

    private function getVisitTrends(Carbon $startDate, Carbon $endDate): array
    {
        return PageVisit::withinPeriod($startDate, $endDate)
            ->groupByDay()
            ->get()
            ->toArray();
    }

    private function getTimeAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        $hourlyData = PageVisit::withinPeriod($startDate, $endDate)
            ->groupByHour()
            ->get()
            ->keyBy('hour')
            ->map(fn($item) => $item->total_visits)
            ->toArray();

        // Fill in missing hours with 0
        $hourlyAnalysis = array_replace(
            array_fill(0, 24, 0),
            $hourlyData
        );

        // Find peak and quiet hours, ensuring we have valid data
        $nonZeroHours = array_filter($hourlyAnalysis);
        $peakHour = !empty($nonZeroHours) ? array_search(max($nonZeroHours), $hourlyAnalysis) : null;
        $quietHour = !empty($nonZeroHours) ? array_search(min($nonZeroHours), $hourlyAnalysis) : null;

        // Calculate daily average
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $totalVisits = array_sum($hourlyAnalysis);
        $dailyAverage = $totalDays > 0 ? round($totalVisits / $totalDays, 2) : 0;

        // Get weekly comparison
        $weeklyComparison = $this->getWeeklyComparison($startDate, $endDate);

        return [
            'hourly' => $hourlyAnalysis,
            'peak_hour' => $peakHour,
            'quiet_hour' => $quietHour,
            'daily_average' => $dailyAverage,
            'weekly_comparison' => $this->getWeeklyComparison($startDate, $endDate),
        ];
    }

    private function getDailyAverage(Carbon $startDate, Carbon $endDate): float
    {
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $totalVisits = PageVisit::withinPeriod($startDate, $endDate)->sum('visit_count');
        
        return round($totalVisits / $totalDays, 2);
    }

    private function getWeeklyComparison(Carbon $startDate, Carbon $endDate): array
    {
        $currentWeekVisits = PageVisit::weekly($endDate)->sum('visit_count');
        $previousWeekVisits = PageVisit::weekly($endDate->copy()->subWeek())->sum('visit_count');
        
        $percentageChange = $previousWeekVisits > 0
            ? round((($currentWeekVisits - $previousWeekVisits) / $previousWeekVisits) * 100, 2)
            : 100;

        return [
            'current_week' => $currentWeekVisits,
            'previous_week' => $previousWeekVisits,
            'percentage_change' => $percentageChange
        ];
    }

    private function getLocationAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        $visits = PageVisit::withinPeriod($startDate, $endDate)
            ->select('country', 'city')
            ->selectRaw('COUNT(*) as visit_count')
            ->groupBy('country', 'city')
            ->orderByDesc('visit_count')
            ->get();

        $countryStats = $visits->groupBy('country')
            ->map(fn($group) => [
                'visits' => $group->sum('visit_count'),
                'cities' => $group->count(),
                'recent_cities' => $group->take(3)->pluck('city'),
                'percent_total' => round(($group->sum('visit_count') / $visits->sum('visit_count')) * 100, 1)
            ])
            ->sortByDesc('visits');

        return [
            'top_countries' => $countryStats->take(10),
            'visit_locations' => $visits->take(10)->map(fn($visit) => [
                'location' => "{$visit->city}, {$visit->country}",
                'count' => $visit->visit_count
            ]),
            'total_countries' => $countryStats->count(),
            'total_cities' => $visits->count()
        ];
    }
} 