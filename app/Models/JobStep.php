<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobStep extends Model
{
    protected $table = 'job_steps';

    protected $fillable = [
        'job_info_id',
        'step_title_fr',
        'step_title'
    ];

    public $timestamps = false;

    public function jobInfo(): BelongsTo
    {
        return $this->belongsTo(JobInfo::class);
    }
}
