<?php

namespace App\Filament\Resources\TributeResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TributeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTributes extends ListRecords
{
    protected static string $resource = TributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
