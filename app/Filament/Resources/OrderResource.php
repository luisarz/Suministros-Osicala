<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DatePicker;
use App\Models\Branch;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\BulkActionGroup;
use App\Filament\Resources\SaleResource\RelationManagers\SaleItemsRelationManager;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Filament\Resources\OrderResource\Pages\CreateOrder;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Tables\Actions\dteActions;
use App\Tables\Actions\orderActions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Filament\Tables\Actions\ActionGroup;
use Filament\Infolists\Components\IconEntry;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Notifications\Notification;


function payment_card($order, array $data, $livewire): void
{
    $items = $order->saleDetails;

    foreach ($items as $item) {
        $discountPercentage = $item->discount ?? 0;
        $price_base = $item->price_base ?? $item->price;

// 1. Calcular precio según tipo de pago
        $price = $data['is_payment_card']
            ? round($price_base * 1.05, 4)  // con tarjeta → +5%
            : round($price_base, 4);        // sin tarjeta → precio base

// 2. Subtotal sin descuento
        $subtotal = $price * $item->quantity;

// 3. Aplicar descuento al subtotal
        $discountAmount = $subtotal * ($discountPercentage / 100);
        $total = round($subtotal - $discountAmount, 4);


        $item->update([
            'price' => $price,
            'total' => $total,
        ]);
    }


    // Totales de la orden
    $order->is_payment_card = $data['is_payment_card'];
    $montoTotal = $order->saleDetails->sum('total');
    $neto = $montoTotal / 1.13;
    $iva = $montoTotal - $neto;

    $order->update([
        'net_amount' => round($neto, 2),
        'taxe' => round($iva, 2),
        'sale_total' => round($montoTotal, 2),
    ]);

    // 👉 Redirigir al mismo recurso (Edit de esta orden)
    $livewire->redirectRoute('filament.admin.resources.orders.edit', [
        'record' => $order->id,
    ]);
}


class OrderResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $label = 'Orden';
    protected static ?string $pluralLabel = 'Órdenes';
    protected static string | \UnitEnum | null $navigationGroup = 'Facturación';

    protected static bool $softDelete = true;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([

                        Grid::make(12)
                            ->schema([

                                Section::make('Orden de Trabajo-Venta')
                                    ->icon('heroicon-o-user')
                                    ->iconColor('success')
                                    ->compact()
                                    ->schema([
                                        DatePicker::make('operation_date')
                                            ->label('Fecha')
                                            ->required()
                                            ->inlineLabel(true)
                                            ->default(now()),

                                        Select::make('seller_id')
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
                                            ->default(fn() => optional(Auth::user()->employee)->id)
                                            ->required()
                                            ->disabled(fn(callable $get) => !$get('wherehouse_id')), // Disable if no wherehouse selected
                                        Select::make('customer_id')
                                            ->searchable()
                                            ->live()
//                                            ->inlineLabel(false)
//                                            ->columnSpanFull()
                                            ->preload()
                                            ->getSearchResultsUsing(function (string $query) {
                                                if (strlen($query) < 2) {
                                                    return []; // No buscar si el texto es muy corto
                                                }

                                                // Buscar clientes por múltiples criterios
                                                return (new Customer)->where('name', 'like', "%{$query}%")
                                                    ->orWhere('last_name', 'like', "%{$query}%")
                                                    ->orWhere('nrc', 'like', "%{$query}%")
                                                    ->orWhere('dui', 'like', "%{$query}%")
                                                    ->orWhere('nit', 'like', "%{$query}%")
                                                    ->select(['id', 'name', 'last_name', 'nrc', 'dui', 'nit'])
                                                    ->limit(50)
                                                    ->get()
                                                    ->mapWithKeys(function ($customer) {
                                                        // Formato para mostrar el resultado en el select
                                                        $displayText = "{$customer->name} {$customer->last_name} - NRC: {$customer->nrc} - DUI: {$customer->dui} - NIT: {$customer->nit}";
                                                        return [$customer->id => $displayText];
                                                    });
                                            })
                                            ->getOptionLabelUsing(function ($value) {
                                                // Obtener detalles del cliente seleccionado
                                                $customer = Customer::find($value); // Buscar el cliente por ID
                                                return $customer
                                                    ? "{$customer->name} {$customer->last_name} - NRC: {$customer->nrc} - DUI: {$customer->dui} - NIT: {$customer->nit}"
                                                    : 'Cliente no encontrado';
                                            })
                                            ->label('Cliente')
                                            ->createOptionForm([
                                                Section::make('Nuevo Cliente')
                                                    ->schema([
                                                        Select::make('wherehouse_id')
                                                            ->label('Sucursal')
                                                            ->inlineLabel(false)
                                                            ->options(function (callable $get) {
                                                                $wherehouse = (Auth::user()->employee)->branch_id;
                                                                if ($wherehouse) {
                                                                    return Branch::where('id', $wherehouse)->pluck('name', 'id');
                                                                }
                                                                return []; // Return an empty array if no wherehouse selected
                                                            })
                                                            ->preload()
                                                            ->default(fn() => optional(Auth::user()->employee)->branch_id)
                                                            ->columnSpanFull(),
                                                        TextInput::make('name')
                                                            ->required()
                                                            ->label('Nombre'),
                                                        TextInput::make('last_name')
                                                            ->required()
                                                            ->label('Apellido'),
                                                    ])->columns(2),
                                            ])
                                            ->createOptionUsing(function ($data) {
                                                return Customer::create($data)->id; // Guarda y devuelve el ID del nuevo cliente
                                            })
                                        ,


                                        Select::make('mechanic_id')
                                            ->label('Mecanico')
                                            ->preload()
                                            ->searchable()
                                            ->live()
                                            ->options(function (callable $get) {
                                                $wherehouse = $get('wherehouse_id');
                                                if ($wherehouse) {
                                                    return Employee::where('branch_id', $wherehouse)
                                                        ->where('job_title_id', 4)
                                                        ->where('is_active', true)
                                                        ->pluck('name', 'id');
                                                }
                                                return []; // Return an empty array if no wherehouse selected
                                            })
                                            ->disabled(fn(callable $get) => !$get('wherehouse_id')), // Disable if no wherehouse selected

                                        Select::make('sales_payment_status')
                                            ->options(['Pagado' => 'Pagado',
                                                'Pendiente' => 'Pendiente',
                                                'Abono' => 'Abono',])
                                            ->label('Estado de pago')
                                            ->default('Pendiente')
                                            ->hidden()
                                            ->disabled(),

                                    ])->columnSpan(9)
                                    ->extraAttributes(['class' => 'bg-blue-100 border border-blue-500 rounded-md p-2'])
                                    ->columns(2),

//                                Section::make('Orden Total' . ($this->getOrderNumber() ?? 'Sin número'))
                                Section::make('')
                                    ->compact()
                                    ->schema([
                                        Placeholder::make('Orden')
                                            ->label('Orden #')
                                            ->content(fn(?Sale $record) => new HtmlString(
                                                '<span style="font-weight: 600; color: #FFFFFF; font-size: 16px; background-color: #0056b3; padding: 4px 8px; border-radius: 5px; display: inline-block;">'
                                                . ($record->order_number ?? '-') .
                                                '</span>'
                                            ))
                                            ->inlineLabel(),
                                        Toggle::make('is_payment_card')
                                            ->inlineLabel()
                                            ->label('Pag. Tarjeta')
                                            ->reactive()
                                            ->afterStateUpdated(function ($set, $state, $get, Component $livewire) {
                                                $order = $livewire->getRecord();
                                                if ($order) {
                                                    payment_card($order, ['is_payment_card' => $state], $livewire);
                                                }
                                            }),

                                        Select::make('wherehouse_id')
                                            ->label('Sucursal')
                                            ->inlineLabel(true)
                                            ->relationship('wherehouse', 'name')
                                            ->preload()
                                            ->default(fn() => optional(Auth::user()->employee)->branch_id)
                                            ->columnSpanFull(),

                                        Placeholder::make('total')
                                            ->label('Total')
                                            ->content(fn(?Sale $record) => new HtmlString('<span style="font-weight: bold; color: red; font-size: 18px;">$ ' . number_format($record->sale_total ?? 0, 2) . '</span>'))
                                            ->inlineLabel()
                                            ->extraAttributes(['class' => 'p-0 text-lg']) // Tailwind classes for padding and font size
                                    ])
                                    ->extraAttributes([
                                        'class' => 'bg-blue-100 border border-blue-500 rounded-md p-2',
                                    ])
                                    ->columnSpan(3)->columns(1),
                            ]),
                    ]),
            ]);
    }


    public
    static function table(Table $table): Table
    {
        return $table
            ->columns([


                TextColumn::make('order_number')
                    ->label('Orden')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('wherehouse.name')
                    ->label('Sucursal')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\columns\TextColumn::make('operation_date')
                    ->label('Fecha')
                    ->date('d-m-Y')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_invoiced')
                    ->boolean()
                    ->tooltip('Facturada')
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->label('Procesada')
                    ->sortable(),
                TextColumn::make('wherehouse.name')
                    ->label('Sucursal')
                    ->numeric()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('seller.name')
                    ->label('Vendedor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mechanic.name')
                    ->label('Mecánico')
                    ->searchable()
                    ->placeholder('No asignado')
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sale_status')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $record->deleted_at ? 'Eliminado' : $state)
                    ->color(fn($state) => match ($state) {
                        'Nueva', 'En proceso' => 'info',
                        'Finalizado' => 'success',
                        'Pendiente' => 'warning',
                        'Anulado', 'Eliminado', 'Cancelada' => 'danger',
                        default => null, // Sin color
                    })
                    ->label('Estado'),


                TextColumn::make('retention')
                    ->label('Retención')
                    ->toggleable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('USD', locale: 'en_US')
                    ->sortable(),
                TextColumn::make('sale_total')
                    ->label('Total')
                    ->money('USD', locale: 'en_US')
                    ->summarize(Sum::make()->label('Total')->money('USD', locale: 'en_US'))
//                    ->summarize(
//                        Sum::make()
//                            ->using(fn (Summarizer $summarizer) => $summarizer
//                                ->query(fn ($query) => $query->where('sale_status', '!=', 'Anulado','Eliminado') // Exclude canceled or deleted orders)
//                                )
//                            )
//                            ->label('Total')
//                            ->money('USD', locale: 'en_US')
//                    )
                    ->sortable(),
                TextColumn::make('discount_percentage')
                    ->label('Descuento')
                    ->suffix('%')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('discount_money')
                    ->label('Taller')
                    ->money('USD', locale: 'en_US')
                    ->sortable(),
                TextColumn::make('total_order_after_discount')
                    ->label('Total - Descuento')
//                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('USD', locale: 'en_US')
                    ->sortable(),
                TextColumn::make('cash')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('change')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),

                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Modificación')
                    ->dateTime()
                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordUrl(null)
            ->filters([
                DateRangeFilter::make('operation_date')
                    ->timePicker24()
                    ->startDate(Carbon::now())
                    ->endDate(Carbon::now()),
                TrashedFilter::make('eliminados')
                    ->label('Eliminados')
                    ->query(fn($query) => $query->withoutGlobalScope(SoftDeletingScope::class))
                    ->default(true),

            ])
            ->persistFiltersInSession()
            ->recordActions([
                orderActions::printOrder(),
                EditAction::make()->label('')->iconSize(IconSize::Large)->color('warning')
                    ->visible(fn($record) => $record->sale_status == 'Nueva' && $record->deleted_at == null
                    ),
                orderActions::closeOrder(),
                orderActions::billingOrden(),
                orderActions::cancelOrder(),
                RestoreAction::make()->label('')->iconSize(IconSize::Large)->color('success'),
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make('Exportar'),
                ]),
            ]);
    }

    public
    static function getRelations(): array
    {
        return [
            SaleItemsRelationManager::class,
        ];
    }

    public
    static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public function getOrderTitle(): string
    {
        return 'Orden Total - ' . ($this->record?->order_number ?? 'Sin número');
    }


}