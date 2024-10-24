<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $label = 'Ventas';
    protected static ?string $navigationGroup = 'Facturación';
    protected static bool $softDelete = true;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la venta')
                    ->columns([
                        'sm' => 2,
                    ])
                    ->compact()
                    ->schema([
                        Forms\Components\Select::make('document_type_id')
                            ->label('Comprobante')
                            ->relationship('documenttype', 'name')
                            ->preload()
                            ->default(1)
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('document_internal_number')
                            ->label('#   Comprobante')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('wherehouse_id')
                            ->label('Sucursal')
                            ->live()
                            ->relationship('wherehouse', 'name')
                            ->preload()
//                            ->hidden()
//                            ->disabled()
                            ->default(fn() => optional(Auth::user()->employee)->branch_id), // Null-safe check

                        Forms\Components\Select::make('seller_id')
                            ->label('Vendedor')
                            ->preload()
                            ->searchable()
                            ->live()
                            ->options(function (callable $get) {
                                $wherehouse = $get('wherehouse_id');
                                if ($wherehouse) {
                                    return Employee::where('branch_id', $wherehouse)->pluck('name', 'id');
                                }
                                return []; // Return an empty array if no wherehouse selected
                            })
                            ->required()
                            ->disabled(fn(callable $get) => !$get('wherehouse_id')), // Disable if no wherehouse selected

                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->preload()
                            ->searchable()
                            ->label('Cliente')
                        ,
                        Forms\Components\Select::make('operation_condition_id')
                            ->relationship('salescondition', 'name')
                            ->label('Condición de venta')
                            ->default(1),
                        Forms\Components\Select::make('payment_method_id')
                            ->label('Método de pago')
                            ->relationship('paymentmethod', 'name')
                            ->preload()
                            ->searchable()
                            ->default(1),
                        Forms\Components\Select::make('sales_payment_status')
                            ->options([
                                'Pagado' => 'Pagado',
                                'Pendiente' => 'Pendiente',
                                'Abono' => 'Abono',
                            ])
                            ->label('Estado de pago')
                            ->default('Pendiente')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Nuevo' => 'Nuevo',
                                'Procesando' => 'Procesando',
                                'Cancelado' => 'Cancelado',
                                'Facturado' => 'Facturado',
                                'Anulado' => 'Anulado',
                            ])
                            ->default('Nuevo')
                            ->required(),
                        Forms\Components\Toggle::make('is_taxed')
                            ->label('Gravado')
                            ->default(true)
                            ->required(),
                        Forms\Components\TextInput::make('net_amount')
                            ->hidden()
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('iva')
                            ->required()
                            ->hidden()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('discount')
                            ->required()
                            ->hidden()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('retention')
                            ->required()
                            ->hidden()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('total')
                            ->required()
                            ->hidden()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('cash')
                            ->required()
                            ->hidden()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\TextInput::make('change')
                            ->hidden()
                            ->required()
                            ->numeric()
                            ->default(0.00),
//                        Forms\Components\TextInput::make('casher_id')
//                            ->numeric()
//                            ->default(null),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('documenttype.name')
                    ->label('Comprobante')
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_internal_number')
                    ->label('#')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wherehouse.name')
                    ->label('Sucursal')
                    ->numeric()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Vendedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salescondition.name')
                    ->label('Condición')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentmethod.name')
                    ->label('Método de pago')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sales_payment_status')
                    ->label('Pago'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado'),
                Tables\Columns\IconColumn::make('is_taxed')
                    ->label('Gravado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('net_amount')
                    ->label('Neto')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('iva')
                    ->label('IVA')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->label('Descuento')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('retention')
                    ->label('Retención')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cash')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('change')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('casher.name')
                    ->label('Cajero')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
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
//                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()->label('Anular'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
          RelationManagers\SaleItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
