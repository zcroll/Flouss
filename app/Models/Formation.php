<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formation extends Model
{
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
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id', 'id');
    }
}
