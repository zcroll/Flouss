<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'text',
        'help_text',
        'option_set_id',
        'is_completed',
        'career_id',
        'degree_id',
        'image_url',
        'image_colour',
        'itemset_id',
    ];

    public function optionSet(): BelongsTo
    {
        return $this->belongsTo(OptionSet::class);
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
        ];
    }
}
