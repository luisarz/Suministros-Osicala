<?php

namespace App\Helpers;

use App\Models\Kardex;
use App\Models\Inventory;

class KardexHelper
{
    public static function createKardexFromInventory(
        Inventory $inventory,
        string $operationType = 'Inventario Inicial',
        string $operationId = '',
        string $operationDetailId = '',
        string $documentType = 'Inventario Inicial',
        string $documentNumber = 'Inventario Inicial',
        string $entity = 'Inventario Inicial',
        string $nationality = 'Salvadoreña'
    )
    {
        $kardex = new Kardex();
        $kardex->branch_id = $inventory->branch_id;
        $kardex->date = now();
        $kardex->operation_type = $operationType;
        $kardex->operation_id = $operationId;
        $kardex->operation_detail_id = $operationDetailId;
        $kardex->document_type = $documentType;
        $kardex->document_number = $documentNumber;
        $kardex->entity = $entity;
        $kardex->nationality = $nationality;
        $kardex->inventory_id = $inventory->id;
        $kardex->previous_stock = 0;
        $kardex->stock_in = $inventory->stock;
        $kardex->stock_out = 0;
        $kardex->stock_actual = $inventory->stock;
        $kardex->money_in = $inventory->cost_without_taxes * $inventory->stock;
        $kardex->money_out = 0;
        $kardex->money_actual = $inventory->cost_without_taxes * $inventory->stock;
        $kardex->sale_price = $inventory->prices->where('is_default', true)->first()->price ?? 0;
        $kardex->purchase_price = $inventory->cost_without_taxes;
        $kardex->save();
    }
}
