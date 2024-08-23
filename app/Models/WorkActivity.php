<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkActivity extends Model
{
use HasFactory;

protected $table = 'work_activities';

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
        'domain_source',
    ];

    public function occupationData()
    {
        return $this->belongsTo(OccupationData::class, 'onetsoc_code', 'onetsoc_code');
    }

    public function contentModelReference()
    {
        return $this->belongsTo(ContentModelReference::class, 'element_id', 'element_id');
    }

    public function scalesReference()
    {
        return $this->belongsTo(ScalesReference::class, 'scale_id', 'scale_id');
    }
}
