<?php

namespace App\Filament\Resources\UnitMeasurementResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\UnitMeasurementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnitMeasurements extends ListRecords
{
    protected static string $resource = UnitMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
