<?php
namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{

    protected $table = 'result';

    protected $fillable = [
        'user_id',
        'scores',
        'jobs',
        'highestTwoScores',
        'Archetype',
    ];

    protected $casts = [
        'scores' => 'array',
        'jobs' => 'array',
        'highestTwoScores' => 'array',
        'Archetype' => 'array',
    ];
}
