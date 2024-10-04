<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchetypeCareerJobMatch extends Model
{
    protected $table = 'archetype_career_job_matches_2';
    
    public $timestamps = false;

    protected $fillable = [
        'archetype_career_id',
        'archetype',
        'career',
        'job_id',
        'job_name',
        'similarity_score',
        'analysis_timestamp',
    ];
}