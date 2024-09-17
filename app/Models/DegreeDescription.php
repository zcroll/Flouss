<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeDescription extends Model
{
    protected $table = 'degree_descriptions';

    protected $fillable = [
        'degree_id',
        'main_description',
        'secondary_description',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }
}
