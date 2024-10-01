<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCertification extends Model
{
    protected $table = 'job_certifications';

    protected $fillable = ['job_info_id', 'certification_title'];

    public function jobInfo()
    {
        return $this->belongsTo(JobInfo::class);
    }
}