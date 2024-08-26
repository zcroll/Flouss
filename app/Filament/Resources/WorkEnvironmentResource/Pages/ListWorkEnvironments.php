<?php

namespace App\Filament\Resources\WorkEnvironmentResource\Pages;

use App\Filament\Resources\WorkEnvironmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkEnvironments extends ListRecords
{
    protected static string $resource = WorkEnvironmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
