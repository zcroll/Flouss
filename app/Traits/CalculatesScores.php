<?php
// File: app/Traits/CalculatesScores.php

namespace App\Traits;

use App\Models\Activity;

trait CalculatesScores
{
    protected function calculateScore($activities, array $responses): array
    {
        $scores = [
            'Realistic' => ['sum' => 0, 'count' => 0],
            'Investigative' => ['sum' => 0, 'count' => 0],
            'Artistic' => ['sum' => 0, 'count' => 0],
            'Social' => ['sum' => 0, 'count' => 0],
            'Enterprising' => ['sum' => 0, 'count' => 0],
            'Conventional' => ['sum' => 0, 'count' => 0]
        ];

        foreach ($activities as $activity) {
            if ($activity instanceof Activity) {
                $response = $responses[$activity->id] ?? null;
                $score = $this->convertResponseToScore($response);
                $weightedScore = $score * $activity->scale;

                // Accumulate weighted scores and count them
                $scores[$activity->category]['sum'] += $weightedScore;
                $scores[$activity->category]['count'] += $activity->scale;
            }
        }

        // Calculate final scores based on the normalization equation
        foreach ($scores as $category => $data) {
            $S = $data['sum'];
            $normalizedScore = ($S - 7.5) / 30 * 9 + 1;

            // Ensure the score is within bounds
            $scores[$category] = max(min($normalizedScore, 10), 1);
        }

        return $scores;
    }

    protected function convertResponseToScore($response): int
    {
        return match ($response) {
            'Strongly Like' => 5,
            'Like' => 4,
            'Unsure' => 3,
            'Dislike' => 2,
            'Strongly Dislike' => 1,
            default => 0,
        };
    }
}
