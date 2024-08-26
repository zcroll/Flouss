<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BigFiveTrait extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'openness',
        'conscientiousness',
        'extraversion',
        'agreeableness',
        'neuroticism',
        'lang',
        'job_name_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'openness' => 'decimal:2',
        'conscientiousness' => 'decimal:2',
        'extraversion' => 'decimal:2',
        'agreeableness' => 'decimal:2',
        'neuroticism' => 'decimal:2',
        'job_name_id' => 'integer',
    ];

    public function jobName(): BelongsTo
    {
        return $this->belongsTo(JobName::class);
    }
}
