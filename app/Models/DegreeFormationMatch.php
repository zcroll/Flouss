<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeFormationMatch extends Model
{
    protected $table = 'degree_formation_matches';

    protected $fillable = [
        'degree_id',
        'degree_name',
        'formation_id',
        'formation_name',
        'similarity_score',
        'analysis_timestamp',
    ];

    protected $casts = [
        'degree_id' => 'integer',
        'formation_id' => 'integer',
        'similarity_score' => 'float',
        'analysis_timestamp' => 'datetime',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }
}