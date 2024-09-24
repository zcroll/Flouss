<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsightCategory extends Model
{
    protected $fillable = [
        'insight_category',
        'insight_category_fr',
        'insight_category_slug',
    ];
}