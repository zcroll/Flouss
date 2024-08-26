<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HollandCodeTrait extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'realistic',
        'investigative',
        'artistic',
        'social',
        'enterprising',
        'conventional',
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
        'realistic' => 'decimal:2',
        'investigative' => 'decimal:2',
        'artistic' => 'decimal:2',
        'social' => 'decimal:2',
        'enterprising' => 'decimal:2',
        'conventional' => 'decimal:2',
        'job_name_id' => 'integer',
    ];

    public function jobName(): BelongsTo
    {
        return $this->belongsTo(JobName::class);
    }
}
