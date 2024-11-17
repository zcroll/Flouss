<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeSkill extends Model
{
    protected $table = 'degree_skills_v2';

    protected $fillable = [
        'degree_id',
        'skill_description',
        'skill_description_fr',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }
}
