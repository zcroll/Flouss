<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $table = 'items';
    
    protected $fillable = [
        'text',
        'help_text',
        'option_set_id',
        'is_completed',
        'career_id',
        'degree_id',
        'image_url',
        'image_colour',
        'item_set_id'
    ];

    protected $casts = [
        'is_completed' => 'boolean'
    ];

    public function itemSet(): BelongsTo
    {
        return $this->belongsTo(ItemSet::class);
    }

    public function optionSet(): BelongsTo
    {
        return $this->belongsTo(OptionSet::class);
    }
}
