<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etablissement extends Model
{
    protected $table = 'etablissement';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', 'nom', 'nomAr', 'sigle', 'categoryEtablissement', 'typeEtablissement',
        'adresse', 'ville', 'codePostal', 'telephone', 'fax', 'email', 'siteWeb',
        'regionFr', 'regionAr', 'adresseFr', 'adresseAr'
    ];

    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class, 'etablissement_id', 'id');
    }
}

