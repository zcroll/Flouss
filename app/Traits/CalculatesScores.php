<?php
// File: app/Traits/CalculatesScores.php

namespace App\Traits;

use App\Models\Activity;

trait CalculatesScores
{
    protected function calculateScore($activities, array $responses): array
    {
        $scores = [
            'Realistic' => 0,
            'Investigative' => 0,
            'Artistic' => 0,
            'Social' => 0,
            'Enterprising' => 0,
            'Conventional' => 0
        ];

        foreach ($activities as $activity) {
            if ($activity instanceof Activity) {

                $response = $responses[$activity->id] ?? null;

                $score = $this->convertResponseToScore($response);

                $scores[$activity->category] += $score * $activity->scale / 2;
            }
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
