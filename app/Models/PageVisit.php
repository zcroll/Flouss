<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PageVisit extends Model
{
    protected $fillable = [
        'user_id',
        'path',
        'full_url', 
        'method',
        'browser',
        'browser_version',
        'platform',
        'device',
        'ip_address',
        'visit_time',
        'referrer',
        'user_agent'
    ];

    protected $casts = [
        'visit_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithinPeriod(Builder $query, Carbon $startDate, Carbon $endDate): Builder
    {
        return $query->whereBetween('visit_time', [$startDate, $endDate]);
    }

    public function scopeTopPages(Builder $query, int $limit = 10): Builder
    {
        return $query->select('path')
            ->selectRaw('COUNT(*) as visit_count')
            ->selectRaw('MAX(visit_time) as last_visit_at')
            ->groupBy('path')
            ->orderByDesc('visit_count')
            ->limit($limit);
    }

    public function scopeGroupByDay(Builder $query): Builder
    {
        return $query->selectRaw('DATE(visit_time) as date')
            ->selectRaw('COUNT(*) as total_visits')
            ->groupBy('date')
            ->orderBy('date');
    }

    public function scopeGroupByHour(Builder $query): Builder
    {
        return $query->selectRaw('HOUR(visit_time) as hour')
            ->selectRaw('COUNT(*) as total_visits')
            ->groupBy('hour')
            ->orderBy('hour');
    }

    public function scopeWeekly(Builder $query, Carbon $date): Builder
    {
        return $query->whereBetween('visit_time', [
            $date->copy()->startOfWeek(),
            $date->copy()->endOfWeek()
        ]);
    }
} 