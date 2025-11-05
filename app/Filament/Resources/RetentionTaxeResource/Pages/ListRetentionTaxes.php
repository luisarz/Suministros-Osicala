<?php

namespace App\Filament\Resources\RetentionTaxeResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\RetentionTaxeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRetentionTaxes extends ListRecords
{
    protected static string $resource = RetentionTaxeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
