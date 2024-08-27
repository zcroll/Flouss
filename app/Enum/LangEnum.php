<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LangEnum: string implements HasColor, HasIcon, HasLabel
{
    case FRENCH = 'fr';
    case ARABIC = 'en';

    public function getLabel(): string
    {
        return match ($this) {
            self::FRENCH => 'French',
            self::ARABIC => 'English',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::FRENCH => 'danger',
            self::ARABIC => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::FRENCH => 'heroicon-o-globe-alt',
            self::ARABIC => 'heroicon-o-book-open',
        };
    }
}
