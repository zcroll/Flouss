<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeJob extends Model
{
    protected $table = 'degree_jobs';

    protected $fillable = [
        'degree_id',
        'job_title',
        'job_description',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }
}