<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Favorite;
class Degree extends Model
{
    // use HasFactory;

    // protected $table = 'degrees';

    protected $fillable = [
        'name',
        'slug',
        'degree_level',
        'education_levels',
        'cip_code',
        'score',
        'salary',
        'satisfaction',
        'satisfaction_raw',
        'image',
        'large_image',
        'link',
        'is_external',
        'is_premium',
        'specializations',
        'category',
    ];

    protected $casts = [
        'degree_level' => 'integer',
        'score' => 'float',
        'salary' => 'integer',
        'satisfaction_raw' => 'float',
        'is_external' => 'boolean',
        'is_premium' => 'boolean',
        'education_levels' => 'array',
    ];

    public function degreeDescription(): HasOne
    {
        return $this->hasOne(DegreeDescription::class);
    }

    public function degreeSkills(): HasMany
    {
        return $this->hasMany(DegreeSkill::class);
    }

    public function degreeJobs(): HasMany
    {
        return $this->hasMany(DegreeJob::class);
    }

    public function degreeFormationMatches(): HasMany
    {
        return $this->hasMany(DegreeFormationMatch::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavorite(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }
    public function degreeJobsRelation(): HasMany
    {
        return $this->hasMany(DegreeJobsRelation::class);
    }
}

