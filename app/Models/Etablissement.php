<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etablissement extends Model
{
    protected $table = 'etablissement';

    // Primary key type
    protected $keyType = 'string';

    // Disabling auto-incrementing since the ID is a string
    public $incrementing = false;

    // Mass assignable attributes
    protected $fillable = [
        'id', 'nom', 'nomAr', 'sigle', 'categoryEtablissement', 'typeEtablissement',
        'adresse', 'ville', 'codePostal', 'telephone', 'fax', 'email', 'siteWeb',
        'regionFr', 'regionAr', 'adresseFr', 'adresseAr'
    ];

    /**
     * Get the formations for the etablissement.
     */
    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class, 'etablissement_id', 'id');
    }
}

