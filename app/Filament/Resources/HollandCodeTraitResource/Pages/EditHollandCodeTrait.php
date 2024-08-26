<?php

namespace App\Filament\Resources\HollandCodeTraitResource\Pages;

use App\Filament\Resources\HollandCodeTraitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHollandCodeTrait extends EditRecord
{
    protected static string $resource = HollandCodeTraitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
