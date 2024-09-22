<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchetypeCareer extends Model
{
    protected $table = 'archetype_careers';

    protected $fillable = [
        'archetype',
        'career',
    ];

    public $timestamps = false;
}