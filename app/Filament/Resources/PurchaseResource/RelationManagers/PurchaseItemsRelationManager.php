<?php

namespace App\Filament\Resources\PurchaseResource\RelationManagers;

use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class PurchaseItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del producto a Comprar')
//                    ->description('Agregue los productos que desea vender')
                    ->icon('heroicon-o-shopping-cart')
                    ->columns(3)
                    ->schema([
                        Select::make('inventory_id')
                            ->label('Producto')
                            ->searchable()
                            ->live()
                            ->debounce(300)
                            ->columnSpanFull()
                            ->inlineLabel(false)
                            ->getSearchResultsUsing(function (string $query) {
                                $whereHouse = \Auth::user()->employee->branch_id;
                                if (strlen($query) < 3) {
                                    return []; // No cargar resultados hasta que haya al menos 3 letras
                                }
                                return Inventory::with('product')
                                    ->where('branch_id', $whereHouse)
                                    ->whereHas('product', function ($q) use ($query) {
                                        $q->where('name', 'like', "%{$query}%")
                                            ->orWhere('sku', 'like', "%{$query}%")
                                            ->orWhere('bar_code', 'like', "%{$query}%");
                                    })
                                    ->limit(50) // Limita el número de resultados para evitar cargas pesadas
                                    ->get()
                                    ->mapWithKeys(function ($inventory) {
                                        $displayText = "{$inventory->product->name} - SKU: {$inventory->product->sku} - Codigo: {$inventory->product->bar_code}";
                                        return [$inventory->id => $displayText];
                                    });
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $inventory = Inventory::with('product')->find($value);
                                return $inventory
                                    ? "{$inventory->product->name} - SKU: {$inventory->product->sku} - Codigo: {$inventory->product->bar_code}"
                                    : 'Producto no encontrado';
                            })
                            ->required(),


                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->step(1)
                            ->numeric()
                            ->debounce(300)
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
                            ->debounce(300)
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
                            ->debounce(300)
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $this->calculateTotal($get, $set);
                            }),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->step(0.01)
                            ->readOnly()
                            ->columnSpan(1)
                            ->required(),

                    ]),


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Sales Item')
            ->columns([
                Tables\Columns\TextColumn::make('inventory.product.name')
                    ->wrap()
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
                    ->modalHeading('Agregar Producto a Compra')
                    ->label('Agregar Producto')
                    ->after(function (PurchaseItem $record,Component $livewire) {
                        $this->updateTotalPurchase($record);
                        $livewire->dispatch('refreshPurchase');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('7xl')
                    ->after(function (PurchaseItem $record,Component $livewire) {
                        $this->updateTotalPurchase($record);
                        $livewire->dispatch('refreshPurchase');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Quitar')
                    ->after(function (PurchaseItem $record,Component $livewire) {
                        $this->updateTotalPurchase($record);
                        $livewire->dispatch('refreshPurchase');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function afterDelete(): void
    {
        $this->updateParentTotal();
    }


    protected function calculateTotal(callable $get, callable $set)
    {
        $quantity = $get('quantity') ?? 0;
        $price = $get('price') ?? 0;
        $discount = $get('discount') / 100 ?? 0;
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

    protected function updateTotalPurchase(PurchaseItem $record)
    {
        $purchase = Purchase::find($record->purchase_id);
        $neto = PurchaseItem::where('purchase_id', $purchase->id)->sum('total');
        $iva = number_format($neto * 0.13, 2);
        $percepcion = 0;
        if ($purchase->have_perception) {
            $percepcion = $neto * 0.1;
        }
        $totalPurche = $neto + $iva + $percepcion;
        $purchase->net_value=$neto;
        $purchase->taxe_value=$iva;
        $purchase->perception_value=$percepcion;
        $purchase->purchase_total = $totalPurche;
        $purchase->save();
    }
}
