<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scale extends Model
{
    protected $table = 'scales';

    protected $fillable = [
        'scale_group',
        'scale_group_slug',
        'name',
        'short_name',
        'scale_id',
        'definition',
        'short_definition',
    ];
}