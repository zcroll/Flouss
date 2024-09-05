<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobInfo extends Model
{
    use HasFactory;

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
}
