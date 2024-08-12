<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnspscReference extends Model
{
//    use HasFactory;

    // Set the table name if it's different from the plural of the model
    protected $table = 'unspsc_reference';

    protected $primaryKey = 'commodity_code';

    // The primary key is not an incrementing integer value, so explicitly set this
    public $incrementing = false;

    // Define fillable properties to allow mass assignment
    protected $fillable = [
        'commodity_code',
        'commodity_title',
        'class_code',
        'class_title',
        'family_code',
        'family_title',
        'segment_code',
        'segment_title'
    ];

    public $timestamps = false;
}
