<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HollandCodeItem extends Model
{
    protected $table = 'holland_code_items';

    protected $fillable = [
        'text',
        'help_text',
        'option_set_id',
        'is_completed',
        'career_id',
        'degree_id',
        'image_url',
        'image_colour',
        'set_id',
        'type',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'type' => 'string',
    ];

    public $timestamps = false;

    // Relationship with HollandCodeSet
    public function set()
    {
        return $this->belongsTo(HollandCodeSet::class, 'set_id');
    }
}
