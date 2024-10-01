<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobAssociation extends Model
{
    protected $table = 'job_associations';

    protected $fillable = ['job_info_id', 'association_title'];

    public function jobInfo()
    {
        return $this->belongsTo(JobInfo::class);
    }
}