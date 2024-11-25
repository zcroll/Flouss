<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionTrait extends Model
{
    public $timestamps = false;

    protected $table = 'question_trait';

    protected $fillable = [
        'question_id',
        'question_type_id',
        'holland_trait',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
