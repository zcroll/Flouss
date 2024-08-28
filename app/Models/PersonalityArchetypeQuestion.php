<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalityArchetypeQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'type',
    ];
}
