<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageVisit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, mixed>
     */
    protected $fillable = [
        'path',
        'route_name',
        'visit_count',
        'last_visit_at',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'last_visit_at' => 'datetime',
        'visit_count' => 'integer'
    ];

    /**
     * Get the formatted path for display.
     */
    public function getFormattedPathAttribute(): string
    {
        return '/' . ltrim($this->path, '/');
    }

    /**
     * Get the display name for the page.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->route_name ?? $this->formatted_path;
    }

    /**
     * Scope a query to only include visits within a date range.
     */
    public function scopeWithinDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('last_visit_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to order by most visited.
     */
    public function scopeMostVisited($query)
    {
        return $query->orderByDesc('visit_count');
    }

    /**
     * Scope a query to get recent visits.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderByDesc('last_visit_at')->limit($limit);
    }

    /**
     * Get the total visits for a specific path.
     */
    public static function getTotalVisitsForPath(string $path): int
    {
        return static::where('path', $path)->value('visit_count') ?? 0;
    }

    /**
     * Get analytics summary.
     */
    public static function getAnalyticsSummary()
    {
        return [
            'total_visits' => static::sum('visit_count'),
            'unique_pages' => static::count(),
            'most_visited' => static::mostVisited()->take(5)->get(),
            'recent_visits' => static::recent()->get()
        ];
    }
}