<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStep extends Model
{
    protected $table = 'job_steps';

    protected $fillable = ['job_info_id', 'step_title'];

    public function jobInfo()
    {
        return $this->belongsTo(JobInfo::class);
    }
}