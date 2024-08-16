<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OccupationData extends Model
{
    protected $fillable = ['onetsoc_code', 'title', 'description'];









    public function getRouteKeyName(): string
    {
        return 'onetsoc_code';
    }

    public function Knowledge(): HasMany{
        return  $this->hasMany(Knowledge::class);
    }    public function Abitity(): HasMany{
        return  $this->hasMany(Ability::class);
    }
}
