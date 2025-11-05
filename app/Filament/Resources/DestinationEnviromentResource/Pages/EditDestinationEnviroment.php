<?php

namespace App\Filament\Resources\DestinationEnviromentResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DestinationEnviromentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDestinationEnviroment extends EditRecord
{
    protected static string $resource = DestinationEnviromentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
