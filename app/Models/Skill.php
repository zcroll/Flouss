<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'skills';

    // Disable timestamps if not used in table
    public $timestamps = false;

    // Fillable fields
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

    // Define relationships if necessary
    public function occupation()
    {
        return $this->belongsTo(OccupationData::class, 'onetsoc_code', 'onetsoc_code');
    }

    public function contentModel()
    {
        return $this->belongsTo(ContentModelReference::class, 'element_id', 'element_id');
    }

    public function scale()
    {
        return $this->belongsTo(ScalesReference::class, 'scale_id', 'scale_id');
    }
}
