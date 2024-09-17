<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class JobFieldController extends Controller
{
    private const FIELD_CODES = [
        'Technology' => ['R', 'I'],
        'Healthcare' => ['S', 'I'],
        'Education' => ['S', 'A'],
        'Finance' => ['C', 'E'],
        'Engineering' => ['R', 'I'],
        'Arts & Entertainment' => ['A', 'S'],
        'Business & Management' => ['E', 'C'],
        'Science & Research' => ['I', 'R'],
        'Law & Public Policy' => ['E', 'S'],
        'Trades & Construction' => ['R', 'E'],
        'Healthcare Administration' => ['C', 'E'],
        'Educational Technology' => ['S', 'R'],
        'Financial Technology (FinTech)' => ['C', 'R'],
        'Environmental Science' => ['I', 'R'],
        'User Experience (UX) Design' => ['A', 'I'],
        'Entrepreneurship' => ['E', 'I'],
    ];

    private const TRAIT_INITIALS = [
        'Realistic' => 'R',
        'Investigative' => 'I',
        'Artistic' => 'A',
        'Social' => 'S',
        'Enterprising' => 'E',
        'Conventional' => 'C',
    ];

    public function index()
    {
        $jobsWithFields = $this->getJobsWithFields();
        ds($jobsWithFields);
        return Inertia::render('JobFields', [
            'jobsWithFields' => $jobsWithFields,
        ]);
    }

    private function getJobsWithFields()
    {
        // Fetch all jobs and their Holland Code scores
        $jobs = DB::table('job_infos')
            ->join('personality_traits', 'job_infos.id', '=', 'personality_traits.job_info_id')
            ->where('personality_traits.trait_type', 'holland_codes')
            ->select('job_infos.id', 'personality_traits.trait_name', 'personality_traits.trait_score')
            ->get();

        // Group jobs by their ID
        $jobsGrouped = $jobs->groupBy('id');

        // Fetch job names separately
        $jobNames = DB::table('job_infos')
            ->whereIn('id', $jobsGrouped->keys())
            ->pluck('name', 'id');

        // Process each job to determine its field
        $jobsWithFields = $jobsGrouped->map(function ($jobTraits) use ($jobNames) {
            $jobScores = $jobTraits->pluck('trait_score', 'trait_name')->toArray();
            $field = $this->determineField($jobScores);

            return [
                'id' => $jobTraits->first()->id,
                'name' => $jobNames[$jobTraits->first()->id],
                'field' => $field,
                'scores' => $jobScores,
            ];
        });

        // Group jobs by field
        return $jobsWithFields->groupBy('field')->sortKeys();
    }

    private function determineField($jobScores)
    {
        // Map full trait names to initials
        $jobScoresWithInitials = [];
        foreach ($jobScores as $trait => $score) {
            if (isset(self::TRAIT_INITIALS[$trait])) {
                $jobScoresWithInitials[self::TRAIT_INITIALS[$trait]] = $score;
            }
        }

        arsort($jobScoresWithInitials);
        $topTwoCodes = array_slice(array_keys($jobScoresWithInitials), 0, 2);

        $bestMatch = ['field' => 'Other', 'score' => 0];

        foreach (self::FIELD_CODES as $field => $codes) {
            $matchScore = $this->calculateMatchScore($topTwoCodes, $codes);
            if ($matchScore > $bestMatch['score']) {
                $bestMatch = ['field' => $field, 'score' => $matchScore];
            }
        }

        return $bestMatch['field'];
    }

    private function calculateMatchScore($jobCodes, $fieldCodes)
    {
        $score = 0;
        foreach ($jobCodes as $index => $code) {
            if (in_array($code, $fieldCodes)) {
                // Give more weight to codes that appear earlier in the list
                $score += (2 - $index) * (2 - array_search($code, $fieldCodes));
            }
        }
        return $score;
    }
}