<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    protected $table = 'options';
    
    protected $fillable = [
        'text',
        'help_text',
        'value',
        'reverse_coded_value',
        'option_set_id'
    ];

    protected $casts = [
        'value' => 'float',
        'reverse_coded_value' => 'float'
    ];

    public function optionSet(): BelongsTo
    {
        return $this->belongsTo(OptionSet::class);
    }
}
