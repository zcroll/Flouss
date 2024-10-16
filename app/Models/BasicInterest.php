<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasicInterest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bigdata.basic_interests';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'question',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category' => 'string',
        'question' => 'string',
    ];
}
