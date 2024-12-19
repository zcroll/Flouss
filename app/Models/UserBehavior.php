<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserBehavior extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type',
        'page_path',
        'element_id',
        'element_class',
        'metadata',
        'user_agent',
        'ip_address',
        'session_id',
        'occurred_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'occurred_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeWithinPeriod($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('occurred_at', [$startDate, $endDate]);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeOnPage($query, string $path)
    {
        return $query->where('page_path', $path);
    }

    public function scopeGroupByHour($query)
    {
        return $query->selectRaw('HOUR(occurred_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour');
    }

    public function scopeGroupByPage($query)
    {
        return $query->selectRaw('page_path, COUNT(*) as count')
            ->groupBy('page_path')
            ->orderByDesc('count');
    }

    public function scopeGroupByEvent($query)
    {
        return $query->selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->orderByDesc('count');
    }
} 