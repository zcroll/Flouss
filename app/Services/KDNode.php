<?php

namespace App\Services;

class KDNode {
    public $point;
    public $left;
    public $right;

    public function __construct(array $point) {
        $this->point = $point;
        $this->left = null;
        $this->right = null;
    }
}
