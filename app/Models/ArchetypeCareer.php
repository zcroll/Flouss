<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArchetypeCareer extends Model
{
    protected $table = 'archetype_careers';
    public $timestamps = false;

    protected $fillable = [
        'archetype',
        'career',
    ];

    public function similarJobs(): HasMany
    {
        return $this->hasMany(ArchetypeCareerJobMatch::class, 'archetype', 'archetype')
            ->where('career', $this->career)
            ->orderBy('similarity_score', 'desc');
    }
}
