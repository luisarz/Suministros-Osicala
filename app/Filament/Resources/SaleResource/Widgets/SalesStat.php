<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use App\Models\Purchase;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStat extends BaseWidget
{
    protected static ?string $pollingInterval = '10s'; // Auto-refrescar cada 10 segundos

    protected function getStats(): array
    {
        $sales_total=Sale::sum('sale_total');
        $purchase_total=Purchase::sum('purchase_total');

        return [
            Stat::make('Total de ventas', $sales_total)
            ->description('Total de ventas realizadas')
            ->icon('heroicon-o-shopping-cart')
                ->chart([0,$sales_total])
                ->color('success')
            ,
            Stat::make('Total de Compras', $purchase_total)
                ->description('Total de compras realizadas')
                ->icon('heroicon-o-shopping-cart')
                ->chart([0,$purchase_total])
                ->color('danger'),

            Stat::make('Utilidad', $sales_total-$purchase_total)
                ->description('Utilidad Total')
                ->icon('heroicon-o-currency-dollar')
                ->chart([0,$sales_total-$purchase_total])
                ->color('success')
        ];
    }
}
