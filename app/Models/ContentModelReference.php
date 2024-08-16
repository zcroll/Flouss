<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentModelReference extends Model
{
/**
* The table associated with the model.
*
* @var string
*/
protected $table = 'content_model_reference';

public function knowledge(): HasMany
    {
        return $this->hasMany(Knowledge::class, 'element_id', 'element_id');
    }
}
