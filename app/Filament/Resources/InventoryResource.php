<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Tribute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;
    protected static ?string $navigationGroup = 'Inventario';
    protected static ?string $label = 'Inventario';


    public static function form(Form $form): Form
    {
        $tax = Tribute::find(1)->select('rate','is_percentage')->first();
        if (!$tax) {
            $tax = (object) ['rate' => 0, 'is_percentage' => false];
        }
        $divider = ($tax->is_percentage) ?100:1;
        $iva = $tax->rate / $divider;
        return $form
            ->schema([
                Forms\Components\Section::make('Informacion del Inventario')
                    ->columns(2)
                    ->compact()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->required()
                            ->inlineLabel(false)
                            ->preload()
                            ->columnSpanFull()
                            ->relationship('product', 'name')
                            ->searchable(['name', 'sku'])
                            ->placeholder('Seleccionar producto')
                            ->loadingMessage('Cargando productos...')
                            ->getOptionLabelsUsing(function ($record) {
                                return "{$record->name} (SKU: {$record->sku})";  // Formato de la etiqueta
                            }),

                        Forms\Components\Select::make('branch_id')
                            ->label('Sucursal')
                            ->placeholder('Seleccionar sucursal')
                            ->relationship('branch', 'name')
                            ->preload()
                            ->searchable(['name'])
                            ->required(),

                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('stock_min')
                            ->label('Stock Minimo')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('stock_max')
                            ->label('Stock Maximo')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('cost_without_taxes')
                            ->required()
                            ->prefix('$')
                            ->label('Costo sin impuestos')
                            ->numeric()
                            ->inputMode('decimal')
                            ->hintColor('red')
                            ->debounce(500) // Espera 500 ms después de que el usuario deje de escribir
                            ->afterStateUpdated(function ($state, callable $set) use ($iva) {
                                $costWithoutTaxes = $state ?: 0; // Valor predeterminado en 0 si está vacío
                                $costWithTaxes = round($costWithoutTaxes * $iva, 2); // Cálculo del costo con impuestos
                                $costWithTaxes += $costWithoutTaxes; // Suma el costo sin impuestos
                                $set('cost_with_taxes', $costWithTaxes); // Actualiza el campo
                            })
                            ->default(0.00),
                        Forms\Components\TextInput::make('cost_with_taxes')
                            ->label('Costo con impuestos')
                            ->required()
                            ->readOnly()
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),
                        Forms\Components\Section::make('Configuración')
                            ->columns(3)
                            ->compact()
                            ->schema([
                                Forms\Components\Toggle::make('is_stock_alert')
                                    ->label('Alerta de stock minimo')
                                    ->default(true)
                                    ->required(),
                                Forms\Components\Toggle::make('is_expiration_date')
                                    ->label('Tiene vencimiento')
                                    ->default(true)
                                    ->required(),
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true)
                                    ->label('Activo')
                                    ->required(),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Producto')
                    ->getStateUsing(function ($record) {
                        return "{$record->product->name} <br>(SKU: {$record->product->sku}, ID: {$record->product->id})";
                    })
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.sku')
                    ->label('SKU')
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_min')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_without_taxes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_with_taxes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_stock_alert')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_expiration_date')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ReplicateAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->headerActions([

            ])
            ->searchable('product.name', 'product.sku', 'branch.name')

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
