<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itemset extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'title',
        'lead_in_text',
        'url',
    ];
}
