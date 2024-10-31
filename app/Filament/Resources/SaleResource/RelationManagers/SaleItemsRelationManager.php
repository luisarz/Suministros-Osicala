<?php

namespace App\Filament\Resources\SaleResource\RelationManagers;

use App\Models\Inventory;
use App\Models\Price;
use App\Models\SaleItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Sale;
use Illuminate\Support\Facades\Log;

class SaleItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'saleDetails';
    protected static ?string $label = 'Prodúctos agregados';
    protected static ?string $pollingInterval = '1s';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del producto a vender')
//                    ->description('Agregue los productos que desea vender')
                    ->icon('heroicon-o-shopping-cart')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('inventory_id')
                            ->label('Producto')
                            ->searchable()
                            ->columnSpanFull()
//                            ->live()
                            ->inlineLabel(false)
                            ->options(function () {
                                $whereHouse = \Auth::user()->employee->branch_id;
                                return Inventory::with('product')
                                    ->where('branch_id', $whereHouse)
                                    ->get()
                                    ->mapWithKeys(function ($inventory) {
                                        $displayText = "{$inventory->product->name} - SKU: {$inventory->product->sku} - Codigo: {$inventory->product->bar_code}";
                                        return [$inventory->id => $displayText];
                                    });
                            })
                            ->required()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $invetory_id = $get('inventory_id');

                                $price = Price::with('inventory')->where('inventory_id', $invetory_id)->Where('is_default', true)->first();
                                if ($price && $price->inventory) {
                                    $set('price', $price->price);
                                    $set('quantity', 1);
                                    $set('discount', 0);
                                    $set('minprice', $price->inventory->cost_with_taxes);

                                } else {
                                    $set('price', $price->price??0);
                                    $set('quantity', 1);
                                    $set('discount', 0);
                                }
                            }),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->step(1)
                            ->numeric()
                            ->live()
                            ->columnSpan(1)
                            ->required()
                            ->live()
                            ->extraAttributes(['onkeyup' => 'this.dispatchEvent(new Event("input"))'])
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $this->calculateTotal($get, $set);
                            }),

                        Forms\Components\TextInput::make('price')
                            ->label('Precio')
                            ->step(0.01)
                            ->numeric()
                            ->columnSpan(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $this->calculateTotal($get, $set);
                            }),

                        Forms\Components\TextInput::make('discount')
                            ->label('Descuento')
                            ->step(0.01)
                            ->prefix('%')
                            ->numeric()
                            ->live()
                            ->columnSpan(1)
                            ->required()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $this->calculateTotal($get, $set);
                            }),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->step(0.01)
                            ->readOnly()
                            ->columnSpan(1)
                            ->required(),

                        Forms\Components\Toggle::make('is_except')
                            ->label('Exento de IVA')
                            ->columnSpan(1)
                            ->live()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $this->calculateTotal($get, $set);
                            }),
                        Forms\Components\TextInput::make('minprice')
                            ->label('Tributos')
                            ->columnSpan(3)
                            ->afterStateUpdated(function (callable $get, callable $set) {

                            }),
                    ]),


            ])
        ;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Sales Item')
            ->columns([
                Tables\Columns\TextColumn::make('inventory.product.name')
                    ->wrap()
//                    ->searchable()
                    ->label('Producto'),
                Tables\Columns\BooleanColumn::make('inventory.product.is_service')
                    ->label('Producto/Servicio')
                    ->trueIcon('heroicon-o-bug-ant') // Icono cuando `is_service` es true
                    ->falseIcon('heroicon-o-cog-8-tooth') // Icono cuando `is_service` es false

                    ->tooltip(function ($record) {
                        return $record->inventory->product->is_service ? 'Es un servicio' : 'No es un servicio';
                    }),



                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->columnSpan(1),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('USD', locale: 'en_US')
                    ->columnSpan(1),
                Tables\Columns\TextColumn::make('discount')
                    ->label('Descuento')
                    ->numeric()
                    ->columnSpan(1),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('USD', locale: 'en_US')
                    ->columnSpan(1),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('7xl')
                    ->modalHeading('Agregar Producto a venta')
                    ->label('Agregar Producto')
                    ->after(function () {
                        $saleId=$this->ownerRecord->id;
                        $totalSale=SaleItem::where('sale_id',$saleId)->sum('total');
                        Sale::where('id',$saleId)->update(['total'=>$totalSale]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('7xl')
                    ->after(function (SaleItem $record) {
                        $sale=Sale::find($record->sale_id);
                        $totalSale=SaleItem::where('sale_id',$sale->id)->sum('total');
                        $sale->total=$totalSale;
                        $sale->save();

                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Quitar')
                    ->after(function (SaleItem $record) {
                        $sale=Sale::find($record->sale_id);
                        $totalSale=SaleItem::where('sale_id',$sale->id)->sum('total');
                        $sale->total=$totalSale;
                        $sale->save();

                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public  function afterUpdate(): void
    {
//        dd($this->model);//
//        $this->model->precios()->each(function ($precio) {
//            if ($precio->is_default) {
//                Price::where('inventory_id', $precio->inventory_id)
//                    ->where('id', '!=', $precio->id)
//                    ->update(['is_default' => false]);
//            }
//        });
    }
    protected function calculateTotal(callable $get, callable $set)
    {
        $quantity = $get('quantity')??0;
        $price = $get('price')??0;
        $discount = $get('discount') / 100 ??0;
        $is_except = $get('is_except');

        $total = $quantity * $price;
        if ($discount > 0) {
            $total -= $total * $discount;
        }
        if ($is_except) {
            $total -= ($total * 0.13);
        }

        $set('total', $total);
    }

}
