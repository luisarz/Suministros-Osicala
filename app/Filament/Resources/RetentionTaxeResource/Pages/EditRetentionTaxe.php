<?php

namespace App\Filament\Resources\RetentionTaxeResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\RetentionTaxeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRetentionTaxe extends EditRecord
{
    protected static string $resource = RetentionTaxeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
