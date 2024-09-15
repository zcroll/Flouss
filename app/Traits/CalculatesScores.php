<?php

namespace App\Traits;

trait CalculatesScores
{
    private const MAX_SCORE = 5;

    public function calculateHollandScores(array $responses): array
    {
        $scores = [];

        foreach ($responses as $response) {
            if ($response['type'] !== 'holland_codes') {
                continue;
            }

            $category = $response['category'];
            $answer = $response['answer'];

            if (!isset($scores[$category])) {
                $scores[$category] = [];
            }

            $scores[$category][] = $this->convertResponseToScore($answer);
        }

        return $this->calculateAverageScores($scores);
    }

    protected function convertResponseToScore($response): int
    {
        return match ($response) {
            'love' => 5,
            'like' => 4,
            'neutral' => 3,
            'dislike' => 2,
            'hate' => 1,
            default => 0,
        };
    }

    private function calculateAverageScores(array $scores): array
    {
        $averageScores = [];

        foreach ($scores as $category => $responses) {
            $totalScore = array_sum($responses);
            $responseCount = count($responses);
            $averageScore = $responseCount > 0 ? $totalScore / $responseCount : 0;
            $normalizedScore = $averageScore / self::MAX_SCORE;
            $averageScores[$category] = round($normalizedScore, 2);
        }

        return $averageScores;
    }
}
