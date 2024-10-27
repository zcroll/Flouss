<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionSet extends Model
{
    protected $table = 'option_sets';
    
    protected $fillable = [
        'name',
        'help_text',
        'type',
        'item_set_id'
    ];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function itemSet(): BelongsTo
    {
        return $this->belongsTo(ItemSet::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
