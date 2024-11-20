<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobStep extends Model
{
    public $timestamps = false;

    public function jobInfo(): BelongsTo
    {
        return $this->belongsTo(JobInfo::class);
    }
}
