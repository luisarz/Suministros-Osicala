<?php

namespace App\Traits\Traits;

use App\Models\CashBoxOpen;
use App\Models\Sale;

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
    public static function getTotal(bool $isOrder = false, bool $isClosedWithoutInvoiced = false): float
    {
        $idCashBoxOpened = self::getOpenCashBoxId(false); // Get the opened cash box ID once

        $query = Sale::where('cashbox_open_id', $idCashBoxOpened)
            ->where('status', 'Finalizado');

        if ($isOrder) {
            $query->where('is_order', true);
            if ($isClosedWithoutInvoiced) {
                $query->where('is_order_closed_without_invoiced', true);
            }
            $column = 'total_order_after_discount'; // For order totals
        } else {
            $query->where('is_order', false);
            $column = 'sale_total'; // For sale totals
        }

        return $query->sum($column);
    }

//    public static function salesTotal(): float
//    {
//        $idCashBoxOpened = self::getOpenCashBoxId(false); // Get the opened cash box ID once
//        return Sale::where('cashbox_open_id', $idCashBoxOpened)
//            ->where('status','Finalizado')
//            ->where('is_order',false)
//            ->sum('sale_total');
//    }
//    public static function orderTotal(): float
//    {
//        $idCashBoxOpened = self::getOpenCashBoxId(false); // Get the opened cash box ID once
//        return Sale::where('cashbox_open_id', $idCashBoxOpened)
//            ->where('status','Finalizado')
//            ->where('is_order',true)
//            ->where('is_order_closed_without_invoiced',true)
//            ->sum('total_order_after_discount');
//    }


}
