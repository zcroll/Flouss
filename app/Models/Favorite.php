<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\JobInfo;
use App\Models\Degree;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'favoritable_id', 'favoritable_type'];

    protected $casts = [
        'favoritable_id' => 'integer',
    ];

    /**
     * Get the parent favoritable model.
     */
    public function favoritable(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    /**
     * Get the user that owns the favorite.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include favorites for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if a model is favorited by a user.
     */
    public static function isFavorited($userId, $modelId, $modelType): bool
    {
        $type = $modelType === 'career' ? JobInfo::class : Degree::class;

        return static::where([
            'user_id' => $userId,
            'favoritable_id' => $modelId,
            'favoritable_type' => $type,
        ])->exists();
    }
}
