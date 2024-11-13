<?php

namespace App\Filament\Resources\CashboxOpenResource\Pages;

use App\Filament\Resources\CashboxOpenResource;
use App\Models\CashBox;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditCashboxOpen extends EditRecord
{
    protected static string $resource = CashboxOpenResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Cerrar Caja';
    }

    public function afterSave():void{
       $record=$this->record->id;
         $cashboxOpen = CashboxOpenResource::getModel()::find($record);
            $cashboxOpen->closed_at = now();
            $cashboxOpen->closed_amount = $cashboxOpen->amount;
            $cashboxOpen->close_employee_id = auth()->user()->employee->id;
            $cashboxOpen->status = 'closed';
            $cashboxOpen->save();

            $cashbox=CashBox::find($cashboxOpen->cashbox_id);
            $cashbox->is_open=0;
            $cashbox->save();
    }
}
