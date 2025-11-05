<?php

namespace App\Filament\Resources\MarcaResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\MarcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarcas extends ListRecords
{
    protected static string $resource = MarcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
