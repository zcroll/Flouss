<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{
    use HasFactory;

    // Set the table name if it's different from the plural of the model
    protected $table = 'knowledge';

    // Define fillable properties to allow mass assignment
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

    // Disable timestamps if your table doesn't have created_at and updated_at columns
    public $timestamps = false;

    // Define the relationship with the related models
    public function occupation()
    {
        return $this->belongsTo(OccupationData::class, 'onetsoc_code', 'onetsoc_code');
    }

    public function contentModelReference()
    {
        return $this->belongsTo(ContentModelReference::class, 'element_id', 'element_id');
    }

    public function scale()
    {
        return $this->belongsTo(ScalesReference::class, 'scale_id', 'scale_id');
    }
}
