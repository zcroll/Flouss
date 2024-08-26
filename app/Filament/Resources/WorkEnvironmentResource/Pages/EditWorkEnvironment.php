<?php

namespace App\Filament\Resources\WorkEnvironmentResource\Pages;

use App\Filament\Resources\WorkEnvironmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkEnvironment extends EditRecord
{
    protected static string $resource = WorkEnvironmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
