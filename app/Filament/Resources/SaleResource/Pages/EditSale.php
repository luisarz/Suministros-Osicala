<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Helpers\KardexHelper;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Provider;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use http\Client;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Session;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;
    public function getTitle(): string|Htmlable
    {
        return '';
    }

    #[On('refreshSale')]
    public function refresh(): void
    {
    }



    public function aftersave()//Disminuir el inventario
    {

        $id_sale = $this->record->id; // Obtener el registro de la compra
        $sale = Sale::with('documenttype', 'customer', 'customer.country')->find($id_sale);
        $salesItem = SaleItem::where('sale_id', $sale->id)->get();
        $client = $sale->customer;
        $documnetType = $sale->documenttype->name;
        $entity = $client->name . ' ' . $client->last_name;
        $pais = $client->country->name ?? 'Salvadoreña';
        foreach ($salesItem as $item) {
            $inventory = Inventory::find($item->inventory_id);

            // Verifica si el inventario existe
            if (!$inventory) {
                \Log::error("Inventario no encontrado para el item de compra: {$item->id}");
                continue; // Si no se encuentra el inventario, continua con el siguiente item
            }

            // Actualiza el stock del inventario
            $newStock = $inventory->stock - $item->quantity;
            $inventory->update(['stock' => $newStock]);

            // Crear el Kardex
            $kardex = KardexHelper::createKardexFromInventory(
                $inventory->branch_id, // Se pasa solo el valor de branch_id (entero)
                $sale->created_at, // date
                'Venta', // operation_type
                $sale->id, // operation_id
                $item->id, // operation_detail_id
                $documnetType, // document_type
                $sale->document_internal_number, // document_number
                $entity, // entity
                $pais, // nationality
                $inventory->id, // inventory_id
                $inventory->stock + $item->quantity, // previous_stock
                0, // stock_in
                $item->quantity, // stock_out
                $newStock, // stock_actual
                0, // money_in
                $item->quantity * $item->price, // money_out
                $inventory->stock * $item->price, // money_actual
                $item->price, // sale_price
                0 // purchase_price
            );

            // Verifica si la creación del Kardex fue exitosa
            if (!$kardex) {
                \Log::error("Error al crear Kardex para el item de compra: {$item->id}");
            }
        }

        // Redirigir después de completar el proceso
        $this->redirect(static::getResource()::getUrl('index'));
    }

}
