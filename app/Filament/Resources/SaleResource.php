<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Http\Controllers\DTEController;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\HistoryDte;
use App\Models\Inventory;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
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
                Section::make('')
                    ->columns([
                        'sm' => 2,
                    ])
//                    ->compact()
                    ->schema([
//                        Section::make([])->columnSpan(5),
//                        Section::make([])->columnSpan(5),
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
                            ->options(function (callable $get) {
                                $documentType = $get('document_type_id');
                                if ($documentType == 2) {
                                    $customer= Customer::whereNotNull('departamento_id')
                                        ->whereNotNull('distrito_id')//MUnicipio
//                                        ->whereNotNull('distrito_id')
                                        ->whereNotNull('economicactivity_id')
//                                        ->whereNotNull('wherehouse_id')
//                                        ->whereNotNull('address')
                                        ->whereNotNull('nrc')
                                        ->whereNotNull('dui')
                                        ->orderBy('name')
                                        ->pluck('name', 'id');
                                    return $customer;
                                }
                                return Customer::orderBy('name')->pluck('name', 'id');
                            })
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
                            ->options(['Pagado' => 'Pagado',
                                'Pendiente' => 'Pendiente',
                                'Abono' => 'Abono',])
                            ->label('Estado de pago')
                            ->default('Pendiente')
                            ->hidden()
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options(['Nuevo' => 'Nuevo',
                                'Procesando' => 'Procesando',
                                'Cancelado' => 'Cancelado',
                                'Facturado' => 'Facturado',
                                'Anulado' => 'Anulado',])
                            ->default('Nuevo')
                            ->hidden()
                            ->required(),
                        Forms\Components\Toggle::make('is_taxed')
                            ->label('Gravado')
                            ->hidden()
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
                    ])->columns(2),]);
    }

    public
    static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('documenttype.name')
                    ->label('Comprobante')
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_internal_number')
                    ->label('#')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_dte')
                    ->boolean()
                    ->tooltip('DTE')
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->label('DTE')
                    ->sortable(),
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
                    ->money('USD', locale: 'en_US')
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
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()->label('Anular'),
                    Tables\Actions\Action::make('dte')
                        ->label('Generar DTE')
                        ->visible(fn($record) => !$record->is_dte) // Mostrar esta acción solo si isdte es false
                        ->icon('heroicon-o-shield-exclamation')
                        ->requiresConfirmation()
                        ->modalHeading('¿Está seguro de enviar el DTE?')
                        ->color('primary')
                        ->form([  // Formulario de opciones en el modal
                            Select::make('tipoEnvio')
                                ->label('Tipo de Envío')
                                ->options([
                                    'normal' => 'Envío Normal',
                                ])
                                ->default('normal')
                                ->required(),
                            Select::make('confirmacion')
                                ->label('Enviar por Email')
                                ->options([
                                    'si' => 'Sí, deseo enviar',
                                    'no' => 'No, no enviar',
                                ])
                                ->required(),
                        ])
                        ->action(function ($record, array $data) {
//                        redirect()->route('sendDTE', ['idVenta' => $record->id]);
                            if ($data['confirmacion'] === 'si') {
                                $dteController = new DTEController();
                                $resultado = $dteController->generarDTE($record->id);
                                if ($resultado['estado'] === 'EXITO') {
                                    Notification::make()
                                        ->title('Envío Exitoso')
                                        ->success()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title('Fallo en envío')
                                        ->danger()
                                        ->body($resultado["mensaje"]) // Concatena las observaciones con saltos de línea
                                        ->send();
                                }
                            } else {
                                Notification::make()
                                    ->title('Se cancelo en envio')
                                    ->warning()
                                    ->send();
                            }
                        }),

                    Tables\Actions\Action::make('Historial')
                        ->label('Historial DTE')
                        ->icon('heroicon-o-scale')
                        ->color('success')
//                        ->visible(fn($record) => $record->is_dte) // Show this action only if is_dte is true
                        ->action(function ($record, $livewire) {
                            // Retrieve the DTE history based on the record
                            $historial = HistoryDte::where('sales_invoice_id', $record->id)->get();

                            // Dispatch browser event with historial data
                            $livewire->dispatchBrowserEvent('show-historial-modal', [
                                'historial' => $historial->toArray(), // Convert to array for JavaScript
                            ]);
                        })
                        ->modalHeading('Historial de Envío DTE')
                        ->modalContent(function ($record) {
                            // Pass the historial data directly to the view
                            $historial = HistoryDte::where('sales_invoice_id', $record->id)->get();
                            return view('DTE.historial-dte', [
                                'record' => $record,
                                'historial' => $historial, // Pass the historial data to the view
                            ]);
                        })
                        ->modalWidth('7xl'),


                ])->iconButton()->icon('heroicon-o-bars-3')->label('Acciones'),


            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public
    static function getRelations(): array
    {
        return [
            RelationManagers\SaleItemsRelationManager::class,
        ];
    }

    public
    static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }


}
