<?php

namespace App\Services;

class KDTree {
    private $k = 6; // Number of dimensions

    public function buildTree(array $points, int $depth = 0): ?KDNode {
        if (empty($points)) {
            return null;
        }

        $axis = $depth % $this->k;
        usort($points, function($a, $b) use ($axis) {
            return $a[$axis] <=> $b[$axis];
        });

        $medianIndex = intdiv(count($points), 2);
        $node = new KDNode($points[$medianIndex]);
        $node->left = $this->buildTree(array_slice($points, 0, $medianIndex), $depth + 1);
        $node->right = $this->buildTree(array_slice($points, $medianIndex + 1), $depth + 1);

        return $node;
    }

    public function findNearestNeighbor(KDNode $root, array $target, int $depth = 0, KDNode $best = null) {
        if ($root === null) {
            return $best;
        }

        $axis = $depth % $this->k;
        $nextBest = $best;
        $nextNode = null;

        if ($best === null || $this->squaredDistance($target, $root->point) < $this->squaredDistance($target, $best->point)) {
            $nextBest = $root;
        }

        if ($target[$axis] < $root->point[$axis]) {
            $nextNode = $root->left;
            $otherNode = $root->right;
        } else {
            $nextNode = $root->right;
            $otherNode = $root->left;
        }

        $nextBest = $this->findNearestNeighbor($nextNode, $target, $depth + 1, $nextBest);

        if ($otherNode !== null) {
            if (pow($target[$axis] - $root->point[$axis], 2) < $this->squaredDistance($target, $nextBest->point)) {
                $nextBest = $this->findNearestNeighbor($otherNode, $target, $depth + 1, $nextBest);
            }
        }

        return $nextBest;
    }

    private function squaredDistance(array $a, array $b): float {
        $distance = 0;
        for ($i = 0; $i < $this->k; $i++) {
            $distance += pow($a[$i] - $b[$i], 2);
        }
        return $distance;
    }
}
