<?php

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

                $scores[$activity->category]['sum'] += $weightedScore;
                $scores[$activity->category]['count'] += $activity->scale;
            }
        }

        foreach ($scores as $category => $data) {
            $S = $data['sum'];
            $normalizedScore = ($S - 7.5) / 30 * 9 + 1;

            $scores[$category] = max(min($normalizedScore, 10), 1);
        }

        return $scores;
    }

    protected function convertResponseToScore($response): int
    {
        return match ($response) {
            'Strongly Like' => 7,
            'Like' => 5,
            'Unsure' => 4,
            'Dislike' => 3,
            'Strongly Dislike' => 0,
            default => 0,
        };
    }
}
