<?php

namespace App\Filament\Resources\BigFiveTraitResource\Pages;

use App\Filament\Resources\BigFiveTraitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBigFiveTrait extends EditRecord
{
    protected static string $resource = BigFiveTraitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
