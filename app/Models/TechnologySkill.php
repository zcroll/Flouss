<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnologySkill extends Model
{

    // Set the table name if it's different from the plural of the model
    protected $table = 'technology_skills';

    // Define fillable properties to allow mass assignment
    protected $fillable = [
        'onetsoc_code',
        'example',
        'commodity_code',
        'hot_technology',
        'in_demand'
    ];

    // Disable timestamps if the table doesn't have created_at and updated_at columns
    public $timestamps = false;

    // Define relationships
    public function occupation()
    {
        return $this->belongsTo(OccupationData::class, 'onetsoc_code', 'onetsoc_code');
    }

    public function unspscReference()
    {
        return $this->belongsTo(UnspscReference::class, 'commodity_code', 'commodity_code');
    }
}
