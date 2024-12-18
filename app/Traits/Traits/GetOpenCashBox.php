<?php

namespace App\Traits\Traits;

use App\Models\CashBoxOpen;

trait GetOpenCashBox
{
    public static function getOpenCashBoxId(): int
    {
        $whereHouse = auth()->user()->employee->branch_id ?? null;
        $cashBoxOpened = CashBoxOpen::with('cashbox')
            ->where('status', 'open')
            ->whereHas('cashbox', function ($query) use ($whereHouse) {
                $query->where('branch_id', $whereHouse);
            })
            ->first();
        return $cashBoxOpened?->id ?? 0;

    }
}
