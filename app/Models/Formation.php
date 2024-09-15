<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Formation extends Model
{
    use HasFactory;

    protected $table = 'formation';

    protected $fillable = [
        
        'nom', 'nomAr', 'descriptionAr', 'descriptionFr', 'diplomeLibelleAr',
        'EtablissementFr', 'EtablissementAr', 'parcoursNomAr', 'parcoursNomFr',
        'parcoursCode', 'priorite', 'diplomeLibelleFr', 'etablissement_id'
    ];

    /**
     * Get the etablissement that owns the formation.
     */
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id', 'id');
    }

    public function jobInfos(): BelongsToMany
    {
        return $this->belongsToMany(JobInfo::class, 'job_formation')
                    ->withPivot('similarity_score');
    }
}
