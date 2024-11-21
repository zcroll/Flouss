<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Feedback extends Model
{
    protected $fillable = [
        'user_id',
        'rating',
        'feedback'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public static function hasUserGivenFeedback($userId)
    {
      return Feedback::where('user_id', $userId)->exists();
    }
  }