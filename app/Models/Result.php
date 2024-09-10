<?php
namespace App\Models;



use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $table = 'result';
     use HasUuid;
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




    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }


}
