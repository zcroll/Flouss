<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobDegree extends Model
{
    protected $table = 'job_degrees';

    protected $fillable = ['job_info_id', 'degree_title'];

    public function jobInfo()
    {
        return $this->belongsTo(JobInfo::class);
    }
}