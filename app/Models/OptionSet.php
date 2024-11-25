<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OptionSet extends Model
{
    protected $table = 'option_sets';
    
    protected $fillable = [
        'name',
        'help_text',
        'type'
    ];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
} 