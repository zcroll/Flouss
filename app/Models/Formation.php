<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Formation extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'diploma',
        'niveau',
        'nom',
        'type_etablissement',
        'ville',
        'discipline',
        'region'
    ];

    /**
     * Get the degrees related to this course with similarity scores
     */
    public function degrees(): BelongsToMany
    {
        return $this->belongsToMany(Degree::class, 'degree_courses', 'course_id', 'degree_id')
                    ->withPivot(['similarity_score', 'degree_name']);
    }
}
