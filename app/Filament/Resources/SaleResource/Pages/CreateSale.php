<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false), // Desactiva "Crear otro"
        ];
    }
}
