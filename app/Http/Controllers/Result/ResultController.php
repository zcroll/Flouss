<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResultResource;
use App\Models\JobInfo;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\ArchetypeCareer;
use App\Models\ArchetypeCareerJobMatch;
use App\Models\Insight;
use App\Models\Persona;
use App\Models\Feedback;
use App\Models\ArchetypeDiscovery;

class ResultController extends Controller
{
    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;

        return $model->$localizedColumn ?? $model->$baseColumn;
    }

    /**
     * @throws \JsonException
     */
    public function results() : \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $userId = Auth::id();
        $firstScore = ResultResource::collection(Result::with('user')->where('user_id', $userId)->latest()->get())->first();

        if (!$firstScore) {
            return to_route('dashboard');
        }

        $archetype = $firstScore->Archetype ?? null;

        // Basic data needed immediately
        return Inertia::render('Result/Results', [
            'userId' => $firstScore->uuid,
            'hasGivenFeedback' => Inertia::defer(fn () => 
                Feedback::hasUserGivenFeedback(Auth::id())
            ),
            
            // Defer heavy data loading
            'jobs' => Inertia::defer(fn () => 
                !empty($firstScore->jobs) 
                    ? (is_string($firstScore->jobs) 
                        ? json_decode($firstScore->jobs, true, 512, JSON_THROW_ON_ERROR) 
                        : $firstScore->jobs)
                    : null,
                'career-data'
            ),
            
            'degrees' => Inertia::defer(fn () => 
                !empty($firstScore->degree)
                    ? (is_string($firstScore->degree) 
                        ? json_decode($firstScore->degree, true, 512, JSON_THROW_ON_ERROR) 
                        : $firstScore->degree)
                    : null,
                'career-data'
            ),
            
            'Archetype' => Inertia::defer(fn () => 
                DB::table('persona')->where('name', $archetype ?? '')->first()
            ),
            
            'archetypeDiscovery' => Inertia::defer(fn () => 
                DB::table('archetype_discoveries')
                    ->where('slug', '=', strtolower($archetype))
                    ->first(),
                'archetype-data'
            ),
        ]);
    }

    public function personality($id): \Inertia\Response
    {
        $userId = Auth::id();
        $scores = ResultResource::collection(Result::with('user')->where('user_id', $userId)->get());

        if ($scores->isEmpty()) {
            return to_route('dashboard');
        }

        $firstScore = $scores->first();
        ds($firstScore->scores);
        
        $archetype = null;
        if (!empty($firstScore->Archetype)) {
            $archetype = $firstScore->Archetype;
        }
        ds(['archetypetest'=>$archetype[0]]);

        $ArchetypeData = DB::table('persona')->where('name', '=',$archetype[0])->first();
        ds(['ArchetypeData'=>$ArchetypeData]);

        $insights = $ArchetypeData ? Insight::where('persona_id', $ArchetypeData->id)
            ->get()
            ->groupBy('insight_category_slug')
            ->map(function ($group) {
                return $group->map(function ($insight) {
                    return $this->getLocalizedColumn($insight, 'insight');
                });
            }) : collect([]);

        // Fetch Archetype Discovery
        $archetypeDiscovery = ArchetypeDiscovery::where('slug', '=', strtolower($archetype[0]))->first();

        ds(['archetypeDiscovery'=>$archetypeDiscovery]);
     
        return Inertia::render('Result/StrategistDescription', [
            "ArchetypeData" => $ArchetypeData ?? [],
            'firstScore' => $firstScore->scores ?? [],
            'Insights' => $insights ?? [],
            'archetypeDiscovery' => [
                'slug' => $archetypeDiscovery->slug,
                'type' => $archetypeDiscovery->type,
                'notification_text' => $archetypeDiscovery->notification_text,
                'verbose_description' => $this->getLocalizedColumn($archetypeDiscovery, 'verbose_description'),
                'image_url' => $archetypeDiscovery->image_url,
                'rationale' => $this->getLocalizedColumn($archetypeDiscovery, 'rationale'),
                'short_descriptor' => $this->getLocalizedColumn($archetypeDiscovery, 'short_descriptor'),
                'verbose_description_header' => $this->getLocalizedColumn($archetypeDiscovery, 'verbose_description_header'),
                'scales_descriptor' => $this->getLocalizedColumn($archetypeDiscovery, 'scales_descriptor'),
                'strengths_body' => $this->getLocalizedColumn($archetypeDiscovery, 'strengths_body'),
                'weaknesses_body' => $this->getLocalizedColumn($archetypeDiscovery, 'weaknesses_body'),
                'scales' => $archetypeDiscovery->scales,
                'name' => $archetypeDiscovery->name
            ] ?? [],
        ]);
    }
}
