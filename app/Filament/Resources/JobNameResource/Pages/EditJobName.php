<?php

namespace App\Filament\Resources\JobNameResource\Pages;

use App\Filament\Resources\JobNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobName extends EditRecord
{
    protected static string $resource = JobNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
