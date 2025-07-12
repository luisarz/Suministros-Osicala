<?php

namespace App\Traits\Traits;

use App\Models\CashBoxOpen;
use App\Models\Sale;
use App\Models\SmallCashBoxOperation;
use Illuminate\Support\Facades\DB;

trait GetOpenCashBox
{
    public static function getOpenCashBoxId(?bool $cashbox): int
    {
        $whereHouse = auth()->user()->employee->branch_id ?? null;
        $cashBoxOpened = CashBoxOpen::with('cashbox')
            ->where('status', 'open')
            ->whereHas('cashbox', fn($query) => $query->where('branch_id', $whereHouse))
            ->first();
        if (!$cashBoxOpened) {
            return 0; // No hay caja abierta
        }
        return $cashbox ? $cashBoxOpened->cashbox->id ?? 0 : $cashBoxOpened->id ?? 0;
    }

    public static function getTotal(bool $isOrder = false, bool $isClosedWithoutInvoiced = false, $documentType = null, $paymentMethod = null): float
    {
        $idCashBoxOpened = self::getOpenCashBoxId(false); // Get the opened cash box ID once

        $query = Sale::where('cashbox_open_id', $idCashBoxOpened)
            ->whereIn('sale_status', ['Facturada', 'Finalizado']);

        if ($isOrder) {
            $query->whereIn('operation_type', ['Order']);
            if ($isClosedWithoutInvoiced) {
                $query->where('is_order_closed_without_invoiced', true);
            }
            $column = 'total_order_after_discount'; // For order totals
        } else {
            $query->whereIn('operation_type', ['Sale', 'Order', 'Quote']);
            if ($documentType !== null) {
                $query->where('document_type_id', $documentType);
            }
            if ($paymentMethod !== null) {
//                dd($paymentMethod);
                $query->whereIn('payment_method_id', $paymentMethod);
            }
            $query->where('is_dte', true);
            $column = 'sale_total'; // For sale totals
        }
        return $query->sum($column);
    }

    public static function minimalCashBoxTotal(?string $operationType): float
    {
        $idCashBoxOpened = self::getOpenCashBoxId(false); // Get the opened cash box ID once
        return SmallCashBoxOperation::where('cash_box_open_id', $idCashBoxOpened)
            ->where('operation', $operationType)
//            ->where('status', 'Finalizado')
            ->whereNull('deleted_at') // Exclude soft-deleted records
            ->sum('amount');
    }
    function obtenerTotalesOrdenYManoObra(): array
    {
        $categoryIds = [56, 57, 58, 59];
        $idCashBoxOpened = self::getOpenCashBoxId(false); // Get the opened cash box ID once
        $totalManoObra = DB::table('sale_items as si')
            ->join('sales as s', 's.id', '=', 'si.sale_id')
            ->join('inventories as i', 'i.id', '=', 'si.inventory_id')
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
            ->whereIn('c.id', $categoryIds)
            ->where('s.operation_type', 'Order')
            ->where('s.sale_status', 'Finalizado')
            ->where('s.cashbox_open_id', $idCashBoxOpened)
            ->whereNull('s.deleted_at')
            ->sum('si.total');

        $totalOrdenes = Sale::where('operation_type', 'Order')
            ->where('sale_status', 'Finalizado')
            ->where('cashbox_open_id', $idCashBoxOpened)
            ->whereNull('deleted_at')
            ->sum('total_order_after_discount');

        return [
            'total_mano_obra' => $totalManoObra,
            'total_ordenes' => $totalOrdenes-$totalManoObra,
        ];
    }


}
