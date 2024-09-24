<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';

    protected $fillable = [
        'name',
        'name_plural',
        'a_an',
        'primary_trait',
        'secondary_trait',
        'description',
        'adjectives',
        'strengths',
        'strengths_short',
        'weaknesses',
        'personality_paragraph',
    ];
}