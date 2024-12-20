<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PageVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\GeoIPService;
use Illuminate\Support\Facades\Log;
use App\Models\IPLocation;

class AnalyticsController extends Controller
{
    public function index()
    {

        // getting time range selection from request
        $timeRange = request()->input('timeRange', 'last7days');

        // Get date range based on selection
        $dates = $this->getDateRange($timeRange);

        // Check if GeoIP service is working
        $geoipStatus = $this->checkGeoIPStatus();

        // Get analytics data
        $analyticsData = [
            'visits' => $this->getVisitsAnalytics($dates),
            'topPages' => $this->getTopPages($dates),
            'userActivity' => $this->getUserActivity($dates),
            'overview' => $this->getOverviewStats($dates),
            'location_analysis' => $this->getLocationAnalytics($dates),
            'geoip_status' => $geoipStatus
        ];

        $visits = IPLocation::select(
            'city',
            'country_name as country',
            'latitude',
            'longitude',
            DB::raw('COUNT(*) as visit_count'),
            DB::raw('ROW_NUMBER() OVER (ORDER BY COUNT(*) DESC) as rank')
        )
        ->whereNotNull('city')
        ->groupBy('city', 'country_name', 'latitude', 'longitude')
        ->orderBy('visit_count', 'desc')
        ->get();

        $citiesData = [
            'cities' => $visits->map(fn($visit) => [
                'rank' => $visit->rank,
                'city' => $visit->city,
                'country' => $visit->country,
                'coordinates' => [
                    'lat' => $visit->latitude,
                    'lng' => $visit->longitude
                ],
                'visitCount' => $visit->visit_count
            ]),
            'totalVisits' => $visits->sum('visit_count'),
            'totalCities' => $visits->count()
        ];

        return Inertia::render('Admin/Dashboard/Analytics/index', [
            'analytics' => $analyticsData,
            'dateRange' => [
                'start' => $dates['start']->format('Y-m-d'),
                'end' => $dates['end']->format('Y-m-d'),
            ],
            'cities' => $citiesData['cities'],
            'totalVisits' => $citiesData['totalVisits'],
            'totalCities' => $citiesData['totalCities']
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

        $locationStats = PageVisit::withinDates($dates['start'], $dates['end'])
            ->select('country')
            ->selectRaw('COUNT(*) as visit_count')
            ->groupBy('country')
            ->orderByDesc('visit_count')
            ->limit(1)
            ->first();

        return [
            'total_visits' => $totalVisits,
            'unique_pages' => PageVisit::withinDates($dates['start'], $dates['end'])->count(),
            'percent_change' => round($percentChange, 1),
            'average_daily_visits' => round($totalVisits / max(1, Carbon::parse($dates['start'])->diffInDays($dates['end']))),
            'top_country' => $locationStats?->country ?? 'Unknown',
            'top_country_visits' => $locationStats?->visit_count ?? 0
        ];
    }

    private function checkGeoIPStatus(): array
    {
        try {
            $geoip = app(GeoIPService::class);
            ds(['testing geoip', $geoip->checkStatus()]);
            return $geoip->checkStatus();
        } catch (\Exception $e) {
            Log::error('GeoIP Status Check Failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'working' => false,
                'message' => 'Service error: ' . $e->getMessage(),
                'api_key_valid' => false,
                'test_location' => null
            ];
        }
    }

    private function getLocationAnalytics($dates): array
    {
        try {
            $visits = PageVisit::withinDates($dates['start'], $dates['end'])
                ->select('country', 'city', 'latitude', 'longitude')
                ->selectRaw('COUNT(*) as visit_count')
                ->groupBy('country', 'city', 'latitude', 'longitude')
                ->orderByDesc('visit_count')
                ->get();

            \Log::info('Location Analytics Query Result:', [
                'total_visits' => $visits->count(),
                'unique_locations' => $visits->pluck('city', 'country')->toArray()
            ]);

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
                    'coordinates' => [
                        'lat' => $visit->latitude,
                        'lng' => $visit->longitude
                    ],
                    'count' => $visit->visit_count,
                    'last_visit' => $visit->last_visit_at?->diffForHumans()
                ]),
                'total_countries' => $countryStats->count(),
                'total_cities' => $visits->count()
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting location analytics: ' . $e->getMessage());
            return [
                'top_countries' => [],
                'visit_locations' => [],
                'total_countries' => 0,
                'total_cities' => 0
            ];
        }
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



    public function MostVisitedCities()
    {
        try {
            $visits = IPLocation::select(
                'city',
                'country_name as country',
                'latitude',
                'longitude',
                DB::raw('COUNT(*) as visit_count'),
                DB::raw('ROW_NUMBER() OVER (ORDER BY COUNT(*) DESC) as rank')
            )
            ->whereNotNull('city')
            ->groupBy('city', 'country_name', 'latitude', 'longitude')
            ->orderBy('visit_count', 'desc')
            ->get();

            ds([
                'cities' => $visits->map(fn($visit) => [
                    'rank' => $visit->rank,
                    'city' => $visit->city,
                    'country' => $visit->country,
                    'coordinates' => [
                        'lat' => $visit->latitude,
                        'lng' => $visit->longitude
                    ],
                    'visitCount' => $visit->visit_count
                ]),
                'totalVisits' => $visits->sum('visit_count'),
                'totalCities' => $visits->count()
            ]);



            return Inertia::render('Admin/Analytics/Cities', [
                'cities' => $visits->map(fn($visit) => [
                    'rank' => $visit->rank,
                    'city' => $visit->city,
                    'country' => $visit->country,
                    'state_prov' => $visit->state_prov,
                    'coordinates' => [
                        'lat' => $visit->latitude,
                        'lng' => $visit->longitude
                    ],
                    'visitCount' => $visit->visit_count
                ]),
                'totalVisits' => $visits->sum('visit_count'),
                'totalCities' => $visits->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting city analytics: ' . $e->getMessage());
            return Inertia::render('Admin/Analytics/Cities', [
                'cities' => [],
                'totalVisits' => 0,
                'totalCities' => 0
            ]);
        }
    }
}
