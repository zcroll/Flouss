<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'degree_level' => 'integer',
        'score' => 'float',
        'salary' => 'integer',
        'satisfaction_raw' => 'float',
        'is_external' => 'boolean',
        'is_premium' => 'boolean',
    ];
}


