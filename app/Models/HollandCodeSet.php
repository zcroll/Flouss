<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HollandCodeSet extends Model
{
    protected $table = 'bigdata.holland_code_sets';

    protected $fillable = [
        'type',
        'title',
        'lead_in_text',
        'url',
    ];

    public $timestamps = false;

    // Relationship with HollandCodeItem
    public function items()
    {
        return $this->hasMany(HollandCodeItem::class, 'set_id');
    }
}
