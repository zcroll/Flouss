<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function jobInfo(): BelongsTo
    {
        return $this->belongsTo(JobInfo::class, 'job_id');
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
}
