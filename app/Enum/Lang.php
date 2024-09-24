<?php

namespace App\Enum;

enum Lang: string
{
    case EN = 'en';
    case FR = 'fr';

    public function label(): string
    {
        return match($this) {
            self::EN => 'English',
            self::FR => 'French',
        };
    }
}