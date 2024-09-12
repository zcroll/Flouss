<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $table = 'formation';

    // Mass assignable attributes
    protected $fillable = [
        'nom', 'nomAr', 'descriptionAr', 'descriptionFr', 'diplomeLibelleAr',
        'EtablissementFr', 'EtablissementAr', 'parcoursNomAr', 'parcoursNomFr',
        'parcoursCode', 'priorite', 'diplomeLibelleFr', 'etablissement_id'
    ];

    /**
     * Get the etablissement that owns the formation.
     */
    public function etablissement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id', 'id');
    }

    public function jobInfos()
    {
        return $this->belongsToMany(JobInfo::class, 'job_formation')
                    ->withPivot('similarity_score');
    }
}
