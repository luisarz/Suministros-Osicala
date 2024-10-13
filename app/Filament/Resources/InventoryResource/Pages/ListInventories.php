<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Resources\InventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Exportar')
                ->icon('heroicon-o-circle-stack')

                ->url('/admin/inventories/export')
                ->size('sm'),
           Actions\Action::make('Importar')
                ->icon('heroicon-o-circle-stack')

                ->url('/admin/inventories/import')
                ->size('sm'),
           Actions\Action::make('Crear')
                ->icon('heroicon-o-circle-stack')

                ->url('/admin/inventories/create')
                ->size('sm'),
        ];
    }
}
