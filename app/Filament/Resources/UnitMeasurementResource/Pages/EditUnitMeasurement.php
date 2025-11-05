<?php

namespace App\Filament\Resources\UnitMeasurementResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\UnitMeasurementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnitMeasurement extends EditRecord
{
    protected static string $resource = UnitMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
