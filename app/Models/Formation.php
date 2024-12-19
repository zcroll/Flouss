<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Formation extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'diploma',
        'niveau', 
        'nom',
        'type_etablissement',
        'ville',
        'discipline',
        'region'
    ];

    /**
     * Apply filters to the query
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nom', 'like', "%{$search}%");
        });

        $query->when($filters['etablissements'] ?? null, function ($query, $etablissements) {
            $query->whereIn('type_etablissement', $etablissements);
        });

        $query->when($filters['diplomas'] ?? null, function ($query, $diplomas) {
            $query->whereIn('diploma', $diplomas);
        });

        $query->when($filters['disciplines'] ?? null, function ($query, $disciplines) {
            $query->whereIn('discipline', $disciplines);
        });

        $query->when($filters['region'] ?? null, function ($query, $region) {
            $query->where('region', $region);
        });
    }

    /**
     * Get filter options for the current dataset
     */
    public static function getFilterOptions(): array
    {
        return [
            'etablissements' => self::distinct()->whereNotNull('type_etablissement')
                ->pluck('type_etablissement')
                ->map(fn($type) => ['id' => $type, 'nom' => $type])
                ->values()
                ->all(),
                
            'disciplines' => self::distinct()->whereNotNull('discipline')
                ->pluck('discipline')
                ->values()
                ->all(),
                
            'diplomas' => self::distinct()->whereNotNull('diploma')
                ->pluck('diploma')
                ->values()
                ->all(),
        ];
    }

    public function degrees(): BelongsToMany
    {
        return $this->belongsToMany(Degree::class, 'degree_courses', 'course_id', 'degree_id')
                    ->withPivot(['similarity_score', 'degree_name']);
    }
}
