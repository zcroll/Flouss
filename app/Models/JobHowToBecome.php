<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobHowToBecome extends Model
{
    use HasFactory;

    protected $table = 'job_how_to_become';

    protected $fillable = [
        'job_id',
        'title',
        'steps',
        'steps_fr',
        'certifications',
        'associations',
    ];

    protected $casts = [
        'steps' => 'array',
        'certifications' => 'array',
        'associations' => 'array',
    ];

    public function jobInfo()
    {
        return $this->belongsTo(JobInfo::class, 'job_id');
    }
}