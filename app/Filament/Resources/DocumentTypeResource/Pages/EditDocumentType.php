<?php

namespace App\Filament\Resources\DocumentTypeResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DocumentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentType extends EditRecord
{
    protected static string $resource = DocumentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
