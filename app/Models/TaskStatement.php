<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatement extends Model
{
    use HasFactory;

    // Set the table name if it's different from the plural of the model
    protected $table = 'task_statements';

    // Define fillable properties to allow mass assignment
    protected $fillable = [
        'onetsoc_code',
        'task_id',
        'task',
        'task_type',
        'incumbents_responding',
        'date_updated',
        'domain_source'
    ];

    // Disable timestamps if the table doesn't have created_at and updated_at columns

    // Define the relationship with the related model
    public function occupation()
    {
        return $this->belongsTo(OccupationData::class, 'onetsoc_code', 'onetsoc_code');
    }
}
