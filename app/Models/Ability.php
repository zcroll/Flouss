<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ability extends Model
{

    protected $table = 'abilities';

    protected $fillable = [
        'onetsoc_code',
        'element_id',
        'scale_id',
        'data_value',
        'n',
        'standard_error',
        'lower_ci_bound',
        'upper_ci_bound',
        'recommend_suppress',
        'not_relevant',
        'date_updated',
        'domain_source'
    ];


    public function occupation(): BelongsTo
    {
        return $this->belongsTo(OccupationData::class, 'onetsoc_code', 'onetsoc_code');
    }

    public function contentModelReference(): BelongsTo
    {
        return $this->belongsTo(ContentModelReference::class, 'element_id', 'element_id');
    }

    public function scale(): BelongsTo
    {
        return $this->belongsTo(ScalesReference::class, 'scale_id', 'scale_id');
    }
}
