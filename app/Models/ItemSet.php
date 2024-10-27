<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemSet extends Model
{
    protected $table = 'item_sets';
    
    protected $fillable = [
        'type',
        'title',
        'lead_in_text',
        'url',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function optionSets(): HasMany
    {
        return $this->hasMany(OptionSet::class);
    }
}
