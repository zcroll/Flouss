<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeJob extends Model
{
    protected $table = 'degree_jobs_relation';

    protected $fillable = [
        'job_id',
        'degree_id',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }

    public function jobInfo(): BelongsTo
    {
        return $this->belongsTo(JobInfo::class, 'job_id');
    }
}
