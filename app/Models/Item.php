<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'text',
        'help_text',
        'option_set_id',
        'is_completed',
        'career_id',
        'degree_id',
        'image_url',
        'image_colour',
        'itemset_id'
    ];

    protected $casts = [
        'is_completed' => 'boolean'
    ];

    public function itemSet(): BelongsTo
    {
        return $this->belongsTo(ItemSet::class, 'itemset_id');
    }

    public function optionSet(): BelongsTo
    {
        return $this->belongsTo(OptionSet::class);
    }
}
