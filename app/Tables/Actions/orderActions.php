<?php

namespace App\Tables\Actions;

use App\Helpers\KardexHelper;
use App\Http\Controllers\OrdenController;
use App\Models\Company;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleItem;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
function OrderCloseKardex($record, $isEntry = false, $operation = ''): bool
{
    $id_sale = $record->id; // Obtener el registro de la venta
    $sale = Sale::with('documenttype', 'customer', 'customer.country')->find($id_sale);
    $salesItem = SaleItem::where('sale_id', $sale->id)->get();
    $client = $sale->customer;
    $documnetType = $sale->documenttype->name ?? 'Orden de venta';
    $entity = $client->name . ' ' . $client->last_name;
    $pais = $client->country->name ?? 'Salvadoreña';

    foreach ($salesItem as $item) {
        $inventory = Inventory::with('product')->find($item->inventory_id);

        // Verifica si el inventario existe
        if (!$inventory) {
            \Log::error("Inventario no encontrado para el item de compra: {$item->id}");
            continue; // Si no se encuentra el inventario, continúa con el siguiente item
        }

        if (!$inventory->product->is_service) {
            // Determinar si es entrada o salida
            $quantityChange = $isEntry ? $item->quantity : -$item->quantity;
            $newStock = $inventory->stock + $quantityChange;

            // Actualizar inventario
            $inventory->update(['stock' => $newStock]);

            // Crear el Kardex
            $kardex = KardexHelper::createKardexFromInventory(
                $inventory->branch_id, // Se pasa solo el valor de branch_id (entero)
                $sale->created_at, // Fecha
                $operation.' Orden ' . $sale->order_number, // Tipo de operación
                $sale->id, // operation_id
                $item->id, // operation_detail_id
                $documnetType, // document_type
                $sale->order_number, // document_number
                $entity, // entity
                $pais, // nationality
                $inventory->id, // inventory_id
                $inventory->stock, // previous_stock
                $isEntry ? $item->quantity : 0, // stock_in
                !$isEntry ? $item->quantity : 0, // stock_out
                $newStock, // stock_actual
                $isEntry ? $item->quantity * $item->price : 0, // money_in
                !$isEntry ? $item->quantity * $item->price : 0, // money_out
                $newStock * $item->price, // money_actual
                $item->price, // sale_price
                0 // purchase_price
            );

            // Verifica si la creación del Kardex fue exitosa
            if (!$kardex) {
                \Log::error("Error al crear Kardex para el item de compra: {$item->id}");
            }
        }
    }

    return true;
}


class orderActions
{
    public static function printOrder(): Action
    {
        return Action::make('printOrder')
            ->label('')
            ->icon('heroicon-o-printer')
            ->iconSize(IconSize::Large)
            ->color('primary')
            ->action(function ($record) {
                //abrir el json en DTEs
                $datos=Sale::with('customer','saleDetails','saleDetails.inventory','documenttype','seller')->find($record->id);
                $configuracion = Company::find(1);
                $pdf = PDF::loadView('order.order-print-pdf', ['datos' => $datos, 'empresa' => $configuracion]);
                return $pdf->download('order.pdf');
            });

    }

    public static function closeOrder(): Action
    {
        return Action::make('closeOrder')
            ->label('')
            ->icon('heroicon-o-arrow-path')
            ->tooltip('Cerrar orden')
            ->iconSize(IconSize::Large)
            ->color('info')
            ->requiresConfirmation() // Solicita confirmación antes de ejecutar la acción
            ->visible(function ($record) {
                return $record->status != 'Finalizado' && $record->status != 'Anulado';
            })
            ->modalHeading('Confirmación!!')
            ->modalSubheading('¿Estás seguro de que deseas cerrar esta orden? Esta acción no se puede deshacer.')
            ->action(function ($record) {
                //Descargar el inventario antes de procesar la orden
                if ($record->status == 'Finalizado') {
                    Notification::make('No se puede cerrar una orden cerrada')
                        ->title('Error al cerrar orden')
                        ->body('No se puede cerrar una orden cerrada')
                        ->danger()
                        ->send();
                    return;
                }

                if (OrderCloseKardex($record,false,'')) {
                    Notification::make('Orden cerrada')
                        ->title('Orden cerrada')
                        ->body('La orden ha sido cerrada correctamente')
                        ->success()
                        ->send();
                    $record->update(['is_order' => true, 'is_order_closed_without_invoiced' => true, 'status' => 'Finalizado']);
                    return;
                }


                Notification::make('Orden cerrada')
                    ->title('Orden cerrada')
                    ->body('La orden ha sido cerrada correctamente')
                    ->success()
                    ->send();
            });

    }

    public static function cancelOrder(): Action
    {
        return Action::make('cancelOrder')
            ->label('')
            ->icon('heroicon-o-archive-box-x-mark')
            ->tooltip('Cancelar orden')
            ->iconSize(IconSize::Large)
            ->color('danger')
            ->requiresConfirmation() // Solicita confirmación antes de ejecutar la acción


            ->visible(function ($record) {
                return  $record->status == 'Finalizado';
            })

            ->modalHeading('Confirmación!!')
            ->modalSubheading('¿Estás seguro de que deseas cerrar esta orden? Esta acción no se puede deshacer.')
            ->action(function ($record) {
                //Descargar el inventario antes de procesar la orden
                // revisar que este finalizada
                if (OrderCloseKardex($record,true,'Anulacion')) {
                    Notification::make('Orden cerrada')
                        ->title('Orden cerrada')
                        ->body('La orden ha sido cerrada correctamente')
                        ->success()
                        ->send();
                    $record->update(['is_order' => true, 'is_order_closed_without_invoiced' => true, 'status' => 'Anulado']);
                    return;
                }
            });

    }


}