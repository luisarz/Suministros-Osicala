<?php

namespace App\Traits\Traits;

use App\Models\CashBoxOpen;
use App\Models\Sale;
use App\Models\SmallCashBoxOperation;

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


}
