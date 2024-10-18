<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobDegree extends Model
{
  protected $table = 'job_degree_relations';

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