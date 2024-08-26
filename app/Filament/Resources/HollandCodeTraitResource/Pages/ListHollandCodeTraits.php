<?php

namespace App\Filament\Resources\HollandCodeTraitResource\Pages;

use App\Filament\Resources\HollandCodeTraitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHollandCodeTraits extends ListRecords
{
    protected static string $resource = HollandCodeTraitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
