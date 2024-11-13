<?php

namespace App\Filament\Resources\CashboxOpenResource\Pages;

use App\Filament\Resources\CashboxOpenResource;
use App\Models\CashBox;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCashboxOpen extends CreateRecord
{
    protected static string $resource = CashboxOpenResource::class;

    public function afterCreate()
    {
        $cashboxOpen = $this->record;
        $cashbox=CashBox::find($cashboxOpen->cashbox_id);
        $cashbox->is_open = 1;
        $cashbox->save();

    }
}
