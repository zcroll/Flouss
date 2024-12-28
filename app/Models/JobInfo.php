<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class JobInfo extends Model
{
    use HasFactory;

    protected $table = 'job_infos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'eucation_level',
        'score',
        'education_level',
        'score',
        'salary',
        'satisfaction',
        'satisfaction_Raw',
        'image',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function jobInfoDuties(): HasMany
    {
        return $this->hasMany(JobInfoDuty::class);
    }

    public function workEnvironments(): HasMany
    {
        return $this->hasMany(WorkEnvironment::class);
    }

    public function jobInfoDetail(): HasMany
    {
        return $this->hasMany(JobInfoDetail::class);
    }

    public function jobInfoTypes(): HasMany
    {
        return $this->hasMany(JobInfoType::class);
    }

    public function workplaces(): HasMany
    {
        return $this->hasMany(Workplace::class);
    }

    public function personalityTraits(): HasMany
    {
        return $this->hasMany(PersonalityTrait::class);
    }

    public function personalityDetails(): HasMany
    {
        return $this->hasMany(PersonalityDetails::class);
    }

    public function formations(): BelongsToMany
    {
        return $this->belongsToMany(Formation::class, 'job_formation')
            ->withPivot('similarity_score');
    }

    public function degreeJobs()
    {
        return $this->hasMany(DegreeJob::class, 'job_title', 'name');
    }

    public function howToBecome()
    {
        return $this->hasOne(JobHowToBecome::class, 'job_id');
    }

    public function jobSteps()
    {
        return $this->hasMany(JobStep::class);
    }

    public function jobCertifications()
    {
        return $this->hasMany(JobCertification::class);
    }

    public function jobAssociations()
    {
        return $this->hasMany(JobAssociation::class);
    }

    public function jobDegrees()
    {
        return $this->hasMany(JobDegree::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavorite(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->favorites()->where('user_id', Auth::id())->exists();
    }
}
