<?php

namespace App\Filament\Resources\BigFiveTraitResource\Pages;

use App\Filament\Resources\BigFiveTraitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBigFiveTraits extends ListRecords
{
    protected static string $resource = BigFiveTraitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
