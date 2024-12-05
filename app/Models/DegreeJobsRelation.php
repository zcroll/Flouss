<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeJobsRelation extends Model
{
    public $timestamps = false;

    protected $table = 'degree_jobs_relation';

    protected $fillable = [
        'degree_id',
        'job_id',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobInfo::class, 'job_id');
    }
}
