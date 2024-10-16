<?php

namespace App\Traits;

use App\Models\HollandCodeItem;

trait CalculatesScores
{
    private const MAX_SCORE = 5;

    public function calculateHollandScores(array $responses): array
    {
        $scores = [];
        $itemCounts = [];

        foreach ($responses as $response) {
            if ($response['type'] !== 'answered' || $response['category'] !== 'Hollands codes') {
                continue;
            }

            $item = HollandCodeItem::find($response['itemId']);
            if (!$item) {
                continue;
            }

            $trait = $item->type;
            $score = $response['answer'];

            if (!isset($scores[$trait])) {
                $scores[$trait] = 0;
                $itemCounts[$trait] = 0;
            }

            $scores[$trait] += $score;
            $itemCounts[$trait]++;
        }

        return $this->normalizeScores($scores, $itemCounts);
    }

    private function normalizeScores(array $scores, array $itemCounts): array
    {
        $normalizedScores = [];

        foreach ($scores as $trait => $totalScore) {
            $count = $itemCounts[$trait];
            $averageScore = $count > 0 ? $totalScore / $count : 0;
            $normalizedScore = $averageScore / self::MAX_SCORE;
            $normalizedScores[$trait] = round($normalizedScore, 2);
        }

        return $normalizedScores;
    }
}
