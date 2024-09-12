<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFormation extends Model
{
    use HasFactory;

    protected $table = 'job_formation';

    public $timestamps = false;

    protected $fillable = [
        'job_id',
        'formation_id',
        'similarity_score',
    ];

    public function jobInfo()
    {
        return $this->belongsTo(JobInfo::class, 'job_id');
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
}
