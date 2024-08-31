<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AdvancedJobMatcher
{
    private const HOLLAND_WEIGHT = 0.6;
    private const BIG_FIVE_WEIGHT = 0.4;
    private const SCORE_MULTIPLIER = 100;
    private const ERROR_RANGE_PERCENT = 1; // 1% margin for error
    private const MARGIN_ERROR_PERCENT = 1; // 10% margin of error for the best job comparison

    public function findBestMatches(array $candidateHolland, array $candidateBigFive, int $limit = 10)
    {
        $jobs = $this->fetchJobData();
        $matches = [];

        foreach ($jobs as $job) {
            $score = $this->calculateJobMatch($candidateHolland, $candidateBigFive, $job);
            if ($score !== null) {  // Ensure only jobs with valid scores are considered
                $matches[] = ['job_info_id' => $job['job_info_id'], 'score' => $score];
            }
        }

        // Sort jobs by score in descending order (highest score first)
        usort($matches, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_slice($matches, 0, $limit); // Return the top $limit matches
    }

    public function getJobBasedOnScore(array $candidateHolland, array $candidateBigFive)
    {
        $jobs = $this->fetchJobData();
        $bestJob = null;
        $bestScore = null;

        foreach ($jobs as $job) {
            $score = $this->calculateJobMatch($candidateHolland, $candidateBigFive, $job);

            if ($bestScore === null || $this->isWithinMarginError($bestScore, $score)) {
                $bestScore = $score;
                $bestJob = $job;
            }
        }

        return $bestJob['job_info_id'] ?? null;
    }

    private function isWithinMarginError($bestScore, $currentScore)
    {
        $marginError = $bestScore * self::MARGIN_ERROR_PERCENT / 100;
        return abs($bestScore - $currentScore) <= $marginError;
    }

    private function fetchJobData()
    {
        return DB::table('personality_traits')
            ->select('job_info_id', 'trait_name', 'trait_score', 'trait_type')
            ->get()
            ->groupBy('job_info_id')
            ->map(function ($traits) {
                $hollandScores = $this->extractTraitScores($traits, 'holland_codes');
                $bigFiveRanges = $this->extractBigFiveRanges($traits);
                return [
                    'job_info_id' => $traits[0]->job_info_id,
                    'holland_code' => $this->generateHollandCode($hollandScores),
                    'big_five_ranges' => $bigFiveRanges,
                ];
            })
            ->values()
            ->all();
    }

    private function extractTraitScores($traits, $traitType)
    {
        return $traits->where('trait_type', $traitType)
            ->pluck('trait_score', 'trait_name')
            ->map(function ($score) {
                return round(floatval($score) * self::SCORE_MULTIPLIER);
            })
            ->toArray();
    }

    private function extractBigFiveRanges($traits)
    {
        $bigFiveScores = $this->extractTraitScores($traits, 'big_five');
        $ranges = [];
        foreach ($bigFiveScores as $trait => $score) {
            if (is_numeric($score)) {
                $errorRange = round($score * self::ERROR_RANGE_PERCENT / 100);
                $ranges[$trait] = [
                    'min' => max(0, $score - $errorRange),
                    'max' => min(self::SCORE_MULTIPLIER, $score + $errorRange)
                ];
            }
        }
        return $ranges;
    }

    private function generateHollandCode(array $hollandScores)
    {
        arsort($hollandScores);
        return implode('', array_slice(array_keys($hollandScores), 0, 3));
    }

    private function calculateJobMatch(array $candidateHolland, array $candidateBigFive, array $job)
    {
        if (empty($job['holland_code']) || empty($job['big_five_ranges'])) {
            return null;
        }

        // Holland Code Matching
        $hollandScore = $this->calculateHollandMatch($candidateHolland, $job['holland_code']);

        // Big Five Matching
        $bigFiveScore = $this->calculateBigFiveMatch($candidateBigFive, $job['big_five_ranges']);

        // Weighted score combining both Holland and Big Five scores
        $weightedScore = ($hollandScore * self::HOLLAND_WEIGHT) + ($bigFiveScore * self::BIG_FIVE_WEIGHT);

        // If weightedScore is too low, this job isn't a good match
        if ($weightedScore <= 0) {
            return null;
        }

        // Compute Correlation between the Candidate's traits and the Job's profile
        $jobProfile = array_merge(
            $this->hollandCodeToScores($job['holland_code']),
            $this->bigFiveRangesToScores($job['big_five_ranges'])
        );
        $candidateProfile = array_merge($candidateHolland, $candidateBigFive);

        $commonTraits = array_intersect_key($jobProfile, $candidateProfile);
        $jobProfile = array_intersect_key($jobProfile, $commonTraits);
        $candidateProfile = array_intersect_key($candidateProfile, $commonTraits);

        $correlation = $this->calculateCorrelation($candidateProfile, $jobProfile);

        // Return final score adjusted by correlation
        return $weightedScore * $correlation;
    }

    private function calculateHollandMatch(array $candidateHolland, string $jobCode)
    {
        $candidateCode = $this->generateHollandCode($candidateHolland);
        $score = 0;
        for ($i = 0; $i < 3; $i++) {
            if (strpos($jobCode, $candidateCode[$i]) !== false) {
                $score += 3 - $i;
            }
        }
        return $score / 6;
    }

    private function calculateBigFiveMatch(array $candidateBigFive, array $jobRanges)
    {
        if (empty($jobRanges)) {
            return 0;
        }

        $score = 0;
        $validTraits = 0;

        foreach ($candidateBigFive as $trait => $value) {
            if (isset($jobRanges[$trait])) {
                $validTraits++;
                if ($value >= $jobRanges[$trait]['min'] && $value <= $jobRanges[$trait]['max']) {
                    $score++;
                }
            }
        }

        return $validTraits > 0 ? $score / $validTraits : 0;
    }

    private function calculateCorrelation(array $a, array $b)
    {
        $n = count($a);
        if ($n != count($b) || $n == 0) {
            return 0;
        }

        $sum_a = array_sum($a);
        $sum_b = array_sum($b);
        $sum_ab = 0;
        $sum_a_squared = 0;
        $sum_b_squared = 0;

        foreach ($a as $key => $value) {
            $sum_ab += ($value * $b[$key]);
            $sum_a_squared += ($value ** 2);
            $sum_b_squared += ($b[$key] ** 2);
        }

        $numerator = ($n * $sum_ab) - ($sum_a * $sum_b);
        $denominator = sqrt((($n * $sum_a_squared) - ($sum_a ** 2)) * (($n * $sum_b_squared) - ($sum_b ** 2)));

        return ($denominator == 0) ? 0 : $numerator / $denominator;
    }

    private function hollandCodeToScores(string $code)
    {
        $traits = ['R', 'I', 'A', 'S', 'E', 'C'];
        $scores = array_fill_keys($traits, 20);  // Base score for non-prominent traits
        for ($i = 0; $i < 3; $i++) {
            $scores[$code[$i]] = 100 - ($i * 20);  // 100, 80, 60 for top 3 traits
        }
        return $scores;
    }

    private function bigFiveRangesToScores(array $ranges)
    {
        return array_map(function ($range) {
            return round(($range['min'] + $range['max']) / 2);
        }, $ranges);
    }
}
