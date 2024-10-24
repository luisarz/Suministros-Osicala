<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Resources\Pages\Page;

class Pos extends Page
{
    protected static string $resource = SaleResource::class;

    protected static string $view = 'filament.resources.sale-resource.pages.pos';
}
