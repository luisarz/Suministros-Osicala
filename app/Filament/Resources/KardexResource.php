<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Maatwebsite\Excel\Excel;
use App\Filament\Resources\KardexResource\Pages\ListKardexes;
use App\Filament\Resources\KardexResource\Pages\CreateKardex;
use App\Filament\Resources\KardexResource\Pages;
use App\Models\Kardex;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Tables\Grouping\Group;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;


class KardexResource extends Resource
{
    protected static ?string $model = Kardex::class;

    protected static ?string $label = 'Kardex productos';
    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('branch_id')
                    ->required()
                    ->numeric(),

                DatePicker::make('date')
                    ->required(),
                TextInput::make('operation_type')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('operation_id')
                    ->label('Tipo de Operación')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('operation_detail_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('document_type')
                    ->label('T. Documento')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('document_number')
                    ->label('Número')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('entity')
                    ->maxLength(255)
                    ->default(null),
//                Forms\Components\TextInput::make('nationality')
//                    ->maxLength(255)
//                    ->default(null),
                TextInput::make('inventory_id')
                    ->required()
                    ->numeric(),
                TextInput::make('previous_stock')
                    ->required()
                    ->numeric(),
                TextInput::make('stock_in')
                    ->required()
                    ->numeric(),
                TextInput::make('stock_out')
                    ->required()
                    ->numeric(),
                TextInput::make('stock_actual')
                    ->required()
                    ->numeric(),
                TextInput::make('money_in')
                    ->required()
                    ->numeric(),
                TextInput::make('money_out')
                    ->required()
                    ->numeric(),
                TextInput::make('money_actual')
                    ->required()
                    ->numeric(),
                TextInput::make('sale_price')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                TextInput::make('purchase_price')
                    ->required()
                    ->numeric()
                    ->default(0.00),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('document_number')
                    ->label('N° Comprobante')
                    ->searchable(),
                TextColumn::make('document_type')
                    ->label('T. Comprobante')
                    ->searchable(),
                TextColumn::make('entity')
                    ->label('Razon Social')
                    ->searchable(),
                TextColumn::make('nationality')
                    ->label('Nacionalidad del proveedor')
                    ->searchable(),

//                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('whereHouse.name')
                    ->label('Sucursal')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('inventory.product.name')
                    ->label('Producto')
//                    ->wrap(50)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('inventory.product.unitmeasurement.description')
                    ->label('Unidad de Medida')
//                    ->wrap(50)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('operation_type')
                    ->label('Operación')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),



                TextColumn::make('previous_stock')
                    ->label('S. Anterior')
                    ->numeric()
                    ->extraAttributes(['class' => ' color-success bg-success-200']) // Agregar clases CSS para el borde
                    ->sortable(),
                ColumnGroup::make('DETALLE DE UNIDADES ( CANT)', [
                    TextColumn::make('stock_in')
                        ->label('Entrada')
                        ->numeric()
                        ->summarize(Sum::make()->label('Entrada'))
                        ->extraAttributes(['class' => 'bg-success-200']) // Agregar clases CSS para el borde

                        ->sortable(),
                    TextColumn::make('stock_out')
                        ->label('Salida')
                        ->numeric()
                        ->summarize(Sum::make()->label('Salida'))
                        ->sortable(),
                    TextColumn::make('stock_actual')
                        ->label('Existencia')
                        ->numeric()
                        ->summarize(Sum::make()
                            ->label('Existencia')
                            ->numeric()
                            ->suffix(new HtmlString(' U'))
                        )
                        ->sortable(),
                ]),
                TextColumn::make('purchase_price')
                    ->money('USD', locale: 'USD')
                    ->label('Precio Compra')
                    ->sortable(),
                TextColumn::make('promedial_cost')
                    ->money('USD', locale: 'USD')
                    ->label('Costo Promedio')
                    ->sortable(),
                ColumnGroup::make('IMPORTE MONETARIO / PC', [

                    TextColumn::make('money_in')
                        ->label('ENTRADA')
                        ->money('USD', locale: 'USD')
                        ->sortable(),
                    TextColumn::make('money_out')
                        ->label('SALIDA')
                        ->money('USD', locale: 'USD')
                        ->sortable(),
                    TextColumn::make('money_actual')
                        ->label('EXISTENCIA')
                        ->money('USD', locale: 'USD')
                        ->sortable(),
                ]),
//                Tables\Columns\TextColumn::make('sale_price')
//                    ->money('USD', locale: 'USD')
//                    ->label('Precio')
//                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->groups([
                Group::make('whereHouse.name')
                    ->label('Sucursal'),
                Group::make('inventory.product.name')
                    ->label('Inventario'),
                Group::make('date')
                    ->date()
                    ->label('Fecha Operación'),
            ])
            ->filters([
                DateRangeFilter::make('date')->timePicker24()
                    ->label('Fecha de venta')
                    ->startDate(Carbon::now())
                    ->endDate(Carbon::now())

            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                    ExportAction::make()
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename(fn($resource) => $resource::getModelLabel() . '-' . date('Y-m-d'))
                                ->withWriterType(Excel::XLSX)
                                ->withColumns([
                                    Column::make('updated_at'),
                                ]),

                        ]),
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
            'index' => ListKardexes::route('/'),
            'create' => CreateKardex::route('/create'),
//            'edit' => Pages\EditKardex::route('/{record}/edit'),
        ];
    }
}
