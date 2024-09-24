<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonaScale extends Model
{
    protected $table = 'persona_scales';

    protected $fillable = [
        'persona_id',
        'scale_id',
        'value',
        'percentile',
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function scale(): BelongsTo
    {
        return $this->belongsTo(Scale::class);
    }
}