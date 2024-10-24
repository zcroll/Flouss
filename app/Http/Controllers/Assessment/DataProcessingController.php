<?php
namespace App\Http\Controllers\Assessment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataProcessingController extends Controller
{
    private array $interestScores = [
        "Athletics" => 2,
        "Beauty & Style" => 2,
        "Information Technology" => 5,
        "Creative Arts" => 4,
        "Flying" => 3,
        "Healthcare service" => 3,
        "Life Science" => 3,
        "Engineering" => 5,
        "Culinary Arts" => 3,
        "Mathematics" => 3,
        "Military" => 3,
        "Green Industry" => 2,
        "Nature and Agriculture" => 2,
        "Finance" => 2,
        "Music" => 2,
        "Professional Advising" => 2,
        "Sales" => 2,
        "Skilled Trades" => 2,
        "Law" => 1,
        "Physical Science" => 1,
        "Social Sciences" => 1,
        "Politics" => 1,
        "Protective Services" => 1,
        "Office Clerical Work" => 1,
        "Teaching" => 1,
        "Creative Writing & Journalism" => 0,
        "Working with Animals" => 0
    ];

    private array $educationPriority = [
        "master's" => 1,
        "doctorate" => 2,
        "bachelor's" => 3,
        "associate" => 4,
        "high school" => 5
    ];

    /**
     * Get matching jobs based on interest scores
     */
    public function index()
    {
        $jobs = $this->getJobRequirements();
        $matches = $this->groupJobsByInterestCombinations($jobs);
        $sortedScores = $this->getSortedUniqueInterestScores();
        $combinations = $this->generateScoreCombinations($sortedScores);
        $finalMatches = $this->processCombinationsToFindMatches($matches, $combinations);

        return response()->json([
            'matches' => array_slice($finalMatches, 0, 30)
        ]);
    }

    /**
     * Group jobs by their interest combinations
     */
    private function groupJobsByInterestCombinations(array $jobs): array
    {
        $matches = [];
        foreach ($jobs as $job) {
            $primaryInterest = $job->primary_interest;
            $secondaryInterest = $job->secondary_interest ?? '';
            $matchScore = $this->calculateMatchScore($primaryInterest, $secondaryInterest);

            $key = "{$primaryInterest}|{$secondaryInterest}";
            if (!isset($matches[$key])) {
                $matches[$key] = [];
            }

            $matches[$key][] = (object)[
                'job_id' => $job->job_id,
                'job_title' => $job->job_title,
                'primary_interest' => $primaryInterest,
                'secondary_interest' => $secondaryInterest,
                'match_score' => $matchScore,
                'education_level' => $job->education_level ?? ''
            ];
        }
        return $matches;
    }

    /**
     * Get sorted unique interest scores
     */
    private function getSortedUniqueInterestScores(): array
    {
        $sortedScores = array_unique(array_values($this->interestScores));
        rsort($sortedScores);
        return $sortedScores;
    }

    /**
     * Generate all possible score combinations
     */
    private function generateScoreCombinations(array $sortedScores): array
    {
        $combinations = [];
        foreach ($sortedScores as $score1) {
            foreach ($sortedScores as $score2) {
                $combinations[] = [$score1, $score2];
            }
        }
        return $combinations;
    }

    /**
     * Process combinations to find matches
     */
    private function processCombinationsToFindMatches(array $matches, array $combinations): array
    {
        $remainingSlots = 30;
        $combinationLimits = [
            0 => 10,  // Primary Score Combination
            1 => 7,   // Secondary Score Combination
            2 => 5,   // Further Score Combinations
            3 => 5,
            4 => 5,
            5 => 5,
            6 => 5,
            7 => 5,
            8 => 5,
            9 => 5
        ];

        $finalMatches = [];
        foreach ($combinations as $idx => $scorePair) {
            if ($remainingSlots <= 0) break;

            [$score1, $score2] = $scorePair;
            $limit = $combinationLimits[$idx] ?? 5;

            $currentCombos = $this->findMatchingInterestCombinations($matches, $score1, $score2);

            foreach ($currentCombos as $combo) {
                if ($remainingSlots <= 0) break;

                $jobList = $matches[$combo] ?? [];
                if (empty($jobList)) continue;

                $this->sortJobsByEducationLevelAndMatchScore($jobList);

                foreach (array_slice($jobList, 0, $limit) as $job) {
                    if ($remainingSlots <= 0) break;
                    $finalMatches[] = $job;
                    $remainingSlots--;
                }
            }
        }

        usort($finalMatches, fn($a, $b) => $b->match_score <=> $a->match_score);
        return $finalMatches;
    }

    /**
     * Find matching interest combinations
     */
    private function findMatchingInterestCombinations(array $matches, int $score1, int $score2): array
    {
        $currentCombos = [];
        foreach ($matches as $key => $jobList) {
            [$primary, $secondary] = explode('|', $key);
            if (($this->interestScores[$primary] ?? 0) == $score1 && 
                ($this->interestScores[$secondary] ?? 0) == $score2) {
                $currentCombos[] = $key;
            }
        }
        return $currentCombos;
    }

    /**
     * Sort jobs by education level and match score
     */
    private function sortJobsByEducationLevelAndMatchScore(array &$jobList): void
    {
        usort($jobList, function($a, $b) {
            $eduPriorityA = $this->educationPriority[strtolower($a->education_level)] ?? 99;
            $eduPriorityB = $this->educationPriority[strtolower($b->education_level)] ?? 99;
            
            if ($eduPriorityA !== $eduPriorityB) {
                return $eduPriorityA - $eduPriorityB;
            }
            return $b->match_score <=> $a->match_score;
        });
    }

    /**
     * Get job requirements from database
     */
    private function getJobRequirements()
    {
        return DB::select("
            WITH RankedInterests AS (
                SELECT 
                    jr.job_id,
                    ji.name as job_title,
                    jr.scale_name,
                    ROW_NUMBER() OVER (PARTITION BY jr.job_id ORDER BY jr.job_id) as interest_rank,
                    ji.education_level
                FROM job_requirement jr
                JOIN job_infos ji ON jr.job_id = ji.id
            )
            SELECT 
                job_id,
                job_title,
                education_level,
                MAX(CASE WHEN interest_rank = 1 THEN scale_name END) as primary_interest,
                MAX(CASE WHEN interest_rank = 2 THEN scale_name END) as secondary_interest
            FROM RankedInterests
            GROUP BY job_id, job_title, education_level
        ");
    }

    
    private function calculateMatchScore(string $primaryInterest, string $secondaryInterest): float
    {
        $primaryScore = $this->interestScores[$primaryInterest] ?? 0;
        $secondaryScore = $this->interestScores[$secondaryInterest] ?? 0;
        
        // Weighted scoring: primary interest has more weight
        return ($primaryScore * 0.7 + $secondaryScore * 0.3) / 5;
    }
}