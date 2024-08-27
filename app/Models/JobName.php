<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobName extends Model
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
        'education_level',
        'lang',
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

    public function jobTypes(): HasMany
    {
        return $this->hasMany(JobType::class);
    }

    public function jobDetails():hasMany
    {
  return $this->hasMany(JobDetail::class);
    }

    public function responsibilities():hasMany
    {
return $this->hasMany(Responsibility::class);
    }

    public function hollandCodeTraits():HasMany
    {
return $this->hasMany(HollandCodeTrait::class);
    }

    public function bigFiveTraits():hasMany
    {
        return $this->hasMany(BigFiveTrait::class);
    }

    public function workEnvironments():hasMany
    {
        return $this->hasMany(WorkEnvironment::class);
    }  public function workplaces():hasMany
    {
        return $this->hasMany(Workplace::class);
    }

}
