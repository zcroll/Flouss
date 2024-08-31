<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class JobMatcherService
{
    // Define the target job's Holland codes and Big Five traits
    private $targetHolland = [
        'Realistic' => 0.20,
        'Investigative' => 0.43,
        'Artistic' => 0.57,
        'Social' => 0.23,
        'Enterprising' => 0.47,
        'Conventional' => 0.33,
    ];

    private $targetBigFive = [
        'Openness' => 0.22,
        'Conscientiousness' => 0.83,
        'Extraversion' => 0.35,
        'Agreeableness' => 0.41,
        'Social Responsibility' => 0.64,
    ];

    public function matchJobs()
    {
        // Retrieve all jobs and their traits from the database
        $hollandRows = DB::table('personality_traits')
            ->where('trait_type', 'holland_codes')
            ->get();

        $bigFiveRows = DB::table('personality_traits')
            ->where('trait_type', 'big_five')
            ->get();

        // Organize Holland data by job_id
        $hollandJobs = [];
        foreach ($hollandRows as $row) {
            $jobId = $row->job_info_id;
            if (!isset($hollandJobs[$jobId])) {
                $hollandJobs[$jobId] = [];
            }
            $hollandJobs[$jobId][$row->trait_name] = (float)$row->trait_score;
        }

        // Organize Big Five data by job_id
        $bigFiveJobs = [];
        foreach ($bigFiveRows as $row) {
            $jobId = $row->job_info_id;
            if (!isset($bigFiveJobs[$jobId])) {
                $bigFiveJobs[$jobId] = [];
            }
            $bigFiveJobs[$jobId][$row->trait_name] = (float)$row->trait_score;
        }

        // Function to calculate Euclidean distance across traits
        function euclideanDistance($target, $candidate)
        {
            $distance = 0;
            foreach ($target as $trait => $value) {
                $distance += pow($value - ($candidate[$trait] ?? 0), 2);
            }
            return sqrt($distance);
        }

        // Calculate Holland and Big Five distances separately
        $hollandDistances = [];
        $bigFiveDistances = [];

        foreach ($hollandJobs as $jobId => $traits) {
            $distance = euclideanDistance($this->targetHolland, $traits);
            $hollandDistances[] = [$jobId, $distance];
        }

        foreach ($bigFiveJobs as $jobId => $traits) {
            $distance = euclideanDistance($this->targetBigFive, $traits);
            $bigFiveDistances[] = [$jobId, $distance];
        }

        // Normalize the distances using MinMaxScaler (manual implementation)
        $hollandDistances = array_map(function($d) { return $d[1]; }, $hollandDistances);
        $bigFiveDistances = array_map(function($d) { return $d[1]; }, $bigFiveDistances);

        $minMaxNormalize = function($distances) {
            $min = min($distances);
            $max = max($distances);
            return array_map(function($d) use ($min, $max) {
                return ($d - $min) / ($max - $min);
            }, $distances);
        };

        $normalizedHollandDistances = $minMaxNormalize($hollandDistances);
        $normalizedBigFiveDistances = $minMaxNormalize($bigFiveDistances);

        // Combine the normalized distances with the respective weights
        $combinedDistances = [];

        foreach ($hollandJobs as $jobId => $traits) {
            $i = array_search($jobId, array_column($hollandDistances, 0));
            $hollandDistance = $normalizedHollandDistances[$i];
            $bigFiveDistance = $normalizedBigFiveDistances[$i] ?? 0;
            $combinedDistance = 0.6 * $hollandDistance + 0.4 * $bigFiveDistance;
            $combinedDistances[$jobId] = $combinedDistance;
        }

        // Sort and get the top 10 closest jobs based on the combined score
        asort($combinedDistances);
        $closestJobs = array_slice($combinedDistances, 0, 10, true);

        // Output the closest jobs
        $results = "Top 10 closest jobs based on 60% Holland and 40% Big Five (normalized):\n";
        foreach ($closestJobs as $jobId => $distance) {
            $results .= "Job ID: " . $jobId . ", Combined Distance: " . $distance . "\n";
        }

        return $results;
    }
}
