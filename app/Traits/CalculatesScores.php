<?php

namespace App\Traits;

use App\Models\Activity;

trait CalculatesScores
{

    private const MAX_SCORE = 5;

    public function calculateScores(array $formattedResponses): array
    {
        $scores = [];

        foreach ($formattedResponses as $category => $traits) {
            foreach ($traits as $trait => $responses) {
                $totalScore = 0;
                $responseCount = count($responses);

                if ($responseCount === 0) {
                    continue; // Skip traits with no responses to avoid division by zero
                }

                foreach ($responses as $response) {
                    $totalScore += $this->convertResponseToScore($response);
                }

                $averageScore = $totalScore / $responseCount;
                $normalizedScore = $averageScore / self::MAX_SCORE;

                $scores[$trait] = round($normalizedScore, 2);
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
