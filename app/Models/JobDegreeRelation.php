<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobDegreeRelation extends Model
{
    protected $table = 'job_degree_relations';

    protected $fillable = [
        'job_id',
        'degree_id',
    ];

    public function jobInfo(): BelongsTo
    {
        return $this->belongsTo(JobInfo::class, 'job_id');
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class, 'degree_id');
    }
}