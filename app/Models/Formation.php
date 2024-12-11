<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

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
     * Scope to filter formations by search term
     */
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('nom', 'like', "%{$search}%");
        });
    }

    /**
     * Get available filter options based on current query
     * Optimized to use a single query with UNION ALL
     */
    public static function getFilterOptions($baseQuery = null)
    {
        $query = $baseQuery ?? static::query();

        // Build subqueries for each filter type
        $etablissementsQuery = $query->clone()
            ->select(
                DB::raw("'etablissement' as type"),
                'type_etablissement as value'
            )
            ->distinct()
            ->whereNotNull('type_etablissement');

        $disciplinesQuery = $query->clone()
            ->select(
                DB::raw("'discipline' as type"),
                'discipline as value'
            )
            ->distinct()
            ->whereNotNull('discipline');

        $diplomasQuery = $query->clone()
            ->select(
                DB::raw("'diploma' as type"),
                'diploma as value'
            )
            ->distinct()
            ->whereNotNull('diploma');

        // Combine all queries using UNION ALL and execute
        $results = $etablissementsQuery
            ->unionAll($disciplinesQuery)
            ->unionAll($diplomasQuery)
            ->get()
            ->groupBy('type')
            ->map(function ($items) {
                return $items->pluck('value')->filter()->values();
            });

        // Format the results
        return [
            'etablissements' => $results->get('etablissement', collect())
                ->map(fn($type) => ['id' => $type, 'nom' => $type])
                ->values()
                ->all(),
            'disciplines' => $results->get('discipline', collect())->all(),
            'diplomas' => $results->get('diploma', collect())->all(),
        ];
    }

    /**
     * Apply all filters to the query
     * Optimized to combine related conditions
     */
    public function scopeApplyFilters($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nom', 'like', "%{$search}%");
        })->when($filters['niveau'] ?? null, function ($query, $niveau) {
            $query->where('niveau', $niveau);
        })->when($filters['disciplines'] ?? null, function ($query, $disciplines) {
            $query->whereIn('discipline', (array)$disciplines);
        })->when($filters['region'] ?? null, function ($query, $region) {
            $query->where('region', $region);
        })->when($filters['etablissements'] ?? null, function ($query, $etablissements) {
            $query->whereIn('type_etablissement', (array)$etablissements);
        })->when($filters['diplomas'] ?? null, function ($query, $diplomas) {
            $query->whereIn('diploma', (array)$diplomas);
        });
    }

    /**
     * Get the degrees related to this course with similarity scores
     */
    public function degrees(): BelongsToMany
    {
        return $this->belongsToMany(Degree::class, 'degree_courses', 'course_id', 'degree_id')
                    ->withPivot(['similarity_score', 'degree_name']);
    }
}
