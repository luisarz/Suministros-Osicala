<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Models\CashBox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use App\Filament\Resources\CashboxOpenResource\Pages\ListCashboxOpens;
use App\Filament\Resources\CashboxOpenResource\Pages\CreateCashboxOpen;
use App\Filament\Resources\CashboxOpenResource\Pages\EditCashboxOpen;
use Exception;
use App\Filament\Resources\CashboxOpenResource\Pages;
use App\Filament\Resources\CashboxOpenResource\RelationManagers;
use App\Models\CashBoxOpen;
use App\Models\Employee;
use App\Services\CashBoxResumenService;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class CashboxOpenResource extends Resource
{
    protected static ?string $model = CashBoxOpen::class;

//    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static ?string $label = "Apertura de Cajas";
    public static string | \UnitEnum | null $navigationGroup = 'Facturación';


    public static function form(Schema $schema): Schema
    {

        $resumen = new CashBoxResumenService();


        return $schema
            ->components([
                Section::make('')
                    ->compact()
                    ->columnSpan(2)
                    ->label('Administracion Aperturas de caja')
                    ->schema([
                        Section::make('Datos de apertura')
                            ->compact()
                            ->icon('heroicon-o-shopping-cart')
                            ->iconColor('success')
                            ->schema([
                                Select::make('cashbox_id')
                                    ->relationship('cashbox', 'description')
                                    ->options(function () {
                                        $whereHouse = auth()->user()->employee->branch_id;
                                        return CashBox::where('branch_id', $whereHouse)
                                            ->where('is_open', '0')
                                            ->get()
                                            ->pluck('description', 'id');
                                    })
                                    ->disabled(function (?CashBoxOpen $record) {
                                        return $record !== null;
                                    })
                                    ->label('Caja')
                                    ->preload()
                                    ->searchable()
                                    ->required(),
                                Select::class::make('open_employee_id')
                                    ->relationship('openEmployee', 'name', function ($query) {
                                        $whereHouse = auth()->user()->employee->branch_id;
                                        $query->where('branch_id', $whereHouse);
                                    })
                                    ->default(auth()->user()->employee->id)
                                    ->visible(function (?CashBoxOpen $record = null) {
                                        return $record === null;

                                    })
                                    ->label('Empleado Apertura')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                DateTimePicker::make('opened_at')
                                    ->label('Fecha de apertura')
                                    ->inlineLabel(true)
                                    ->default(now())
                                    ->visible(function (?CashBoxOpen $record = null) {
                                        return $record === null;

                                    })
                                    ->required(),
                                TextInput::make('open_amount')
                                    ->label('Monto Apertura')
                                    ->required()
                                    ->numeric()
                                    ->disabled(function (?CashBoxOpen $record) {
                                        return $record !== null;
                                    })
                                    ->label('Monto Apertura'),
                            ])->columns(2)
                        ,
                        Section::make('')
                            ->hidden(function (?CashBoxOpen $record = null) {
                                if ($record === null) {
                                    return true;
                                }
                            })
                            ->schema([
                                Section::make('Ingresos')
                                    ->schema([
                                        Placeholder::make('ingreso_factura')
                                            ->label('Factura')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->ingreso_factura, 2) . '</span>');
                                            }),
                                        Placeholder::make('ingreso_ccf')
                                            ->label('CCF')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->ingreso_ccf, 2) . '</span>');
                                            }),
                                        Placeholder::make('ingreso_ordenes')
                                            ->label('Ordenes')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
//                                                $ingreso_ordenes = (new GetCashBoxOpenedService())->getTotal(true, true);
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->ingreso_ordenes, 2) . '</span>');
                                            }),
                                        Placeholder::make('ingreso_taller')
                                            ->label('Taller')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
//                                                $ingreso_ordenes = (new GetCashBoxOpenedService())->getTotal(true, true);
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->ingreso_taller, 2) . '</span>');
                                            }),

                                        Placeholder::make('ingreso_caja_chica')
                                            ->label('Caja Chica')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
//                                                $ingreso_caja_chica = (new GetCashBoxOpenedService())->minimalCashBoxTotal('Ingreso');
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->ingreso_caja_chica, 2) . '</span>');
                                            }),
                                        Placeholder::make('ingreso_totales')
                                            ->label('INGRESOS TOTALES')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px; border-top: #1e2c2e solid 1px;">$ ' . number_format($resumen->ingreso_total, 2) . '</span>');
                                            }),
                                    ])->columnSpan(1),
                                Section::make('Egresos')
                                    ->schema([
                                        Placeholder::make('egreso_caja_chica')
                                            ->label('Caja Chica')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
//                                                $smalCashBoxEgresoTotal = (new GetCashBoxOpenedService())->minimalCashBoxTotal('Egreso');
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->egreso_caja_chica, 2) . '</span>');
                                            }),
                                        Placeholder::make('egreso_nc')
                                            ->label('Notas de Crédito')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
//                                                $smalCashBoxEgresoTotal = (new GetCashBoxOpenedService())->getTotal(false, false, 5);
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->egreso_nc, 2) . '</span>');
                                            }),
                                        Placeholder::make('egresos_totales')
                                            ->label('EGRESOS TOTALES')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
                                                return new HtmlString('<span style="font-weight: bold; color:red; font-size: 15px; border-top: #1e2c2e solid 1px;">-   $ ' . number_format($resumen->egreso_total, 2) . '</span>');
                                            }),
                                    ])->columnSpan(1),
                                Section::make('Saldos')
                                    ->schema([
                                        Placeholder::make('saldo_efectivo_ventas')
                                            ->label('Efectivo Ventas')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
//                                                $smalCashBoxEgresoTotal = (new GetCashBoxOpenedService())->getTotal(false, false, null, [1]);
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->saldo_efectivo_ventas, 2) . '</span>');
                                            }),
                                        TextInput::make('saldo_tarjeta')
                                            ->label('Tarjeta')
                                            ->readonly()
                                            ->inlineLabel(true)
                                            ->afterStateHydrated(function ($component, $state) use ($resumen) {
                                                $component->state(number_format($resumen->saldo_tarjeta,2)); // fija el valor en el state
                                            }),
//                                            ->content(function () use ($resumen) {
////                                                $smalCashBoxEgresoTotal = (new GetCashBoxOpenedService())->getTotal(false, false, null, [2, 3]);
//                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->saldo_tarjeta, 2) . '</span>');
//                                            }),
                                        TextInput::make('saldo_cheque')
                                            ->label('Cheques')
                                            ->inlineLabel(true)
                                            ->readonly()
                                            ->afterStateHydrated(function ($component, $state) use ($resumen) {
                                                $component->state(number_format($resumen->saldo_cheques,2)); // fija el valor en el state
                                            }),
//                                            ->content(function () use ($resumen) {
////                                                $smalCashBoxEgresoTotal = (new GetCashBoxOpenedService())->getTotal(false, false, null, [4, 5]);
//                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->saldo_cheques, 2) . '</span>');
//                                            }),
                                        Placeholder::make('saldo_efectivo_ordenes')
                                            ->label('Efectivo Ordenes')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
//                                                $smalCashBoxEgresoTotal = $openedCashBox = (new GetCashBoxOpenedService())->getTotal(true, true);;
                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->saldo_efectivo_ordenes, 2) . '</span>');
                                            }),
                                        TextInput::make('saldo_caja_chica')
                                            ->label('Caja Chica')
                                            ->readonly()
                                            ->inlineLabel(true)
                                            ->afterStateHydrated(function ($component, $state) use ($resumen) {
                                                $component->state(number_format($resumen->saldo_caja_chica,2)); // fija el valor en el state
                                            }),
//                                            ->content(function () use ($resumen) {
////                                                $smalCashBoxIngresoTotal = (new GetCashBoxOpenedService())->minimalCashBoxTotal('Ingreso');
//                                                return new HtmlString('<span style="font-weight: bold; font-size: 15px;">$ ' . number_format($resumen->saldo_caja_chica, 2) . '</span>');
//                                            }),
                                        Placeholder::make('saldo_egresos_totales')
                                            ->label('-EGRESOS TOTALES')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {

                                                return new HtmlString('<span style="font-weight: bold; color:red; font-size: 15px;">-$ ' . number_format($resumen->egreso_total, 2) . '</span>');
                                            }),
                                        Placeholder::make('saldo_total_operaciones')
                                            ->label('SALDOS OPERACIONES')
                                            ->inlineLabel(true)
                                            ->content(function () use ($resumen) {
                                                return new HtmlString('<span style=" border-top: #1e2c2e solid 1px; color:green; font-weight:  bold; font-size: 15px;">$ ' . number_format($resumen->saldo_total, 2) . '</span>');
                                            }),
                                    ])->columnSpan(1),
                                Section::make('Datos de cierre')
                                    ->compact()
                                    ->icon('heroicon-o-shield-check')
                                    ->iconColor('danger')
                                    ->hidden(function (?CashBoxOpen $record = null) {
                                        if ($record === null) {
                                            return true;
                                        }
                                    })
                                    ->schema([

                                        TextInput::make('cant_cien')
                                            ->label('100')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_cincuenta')
                                            ->label('50')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_veinte')
                                            ->label('20')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_diez')
                                            ->label('10')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_cinco')
                                            ->label('5')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_uno')
                                            ->label('1')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_cora')
                                            ->label('0.25')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_cero_diez')
                                            ->label('0.10')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_cero_cinco')
                                            ->label('0.05')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('cant_cero_cero_uno')
                                            ->label('0.01')
                                            ->prefix('$')
                                            ->default(0)
                                            ->numeric()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, callable $set) {
                                                static::calcularTotal($get, $set);
                                            })
                                            ->required()
                                            ->inlineLabel(true),

                                        TextInput::make('total_efectivo')
                                            ->label('Total Efectivo')
                                            ->prefix('$')
                                            ->default(0)
                                            ->readonly()
                                            ->required()
                                            ->inlineLabel(true),

                                    ])->columnSpan(1)->columns(2),


                            ])->columns(2)
                        ,
                        Section::make('Cierre')
                            ->hidden(function (?CashBoxOpen $record = null) {
                                if ($record === null) {
                                    return true;
                                }
                            })
                            ->columns(5)
                            ->schema([
                                DateTimePicker::make('closed_at')
                                    ->label('Fecha de cierre')
                                    ->required()
                                    ->inlineLabel(false)
                                    ->default(now())
                                    ->hidden(function (?CashBoxOpen $record = null) {
                                        return $record === null;
                                    }),

                                Placeholder::make('closed_amount')
                                    ->label('Monto Cierre')
                                    ->inlineLabel(false)
                                    ->content(function (callable $get) use ($resumen) {
                                        $montoApertura = round($get('open_amount') ?? 0, 2);

                                        $totalInCash = $resumen->saldo_total + $montoApertura;//($montoApertura + $totalInresos + $totalOrder + $totalSale) - $totalEgresos;
                                        return new HtmlString('<span style=" border-top: #1e2c2e solid 1px; color:green; font-weight:  bold; font-size: 15px;">$ ' . number_format($totalInCash, 2) . '</span>');
                                    })
                                    ->hidden(function (?CashBoxOpen $record = null) {
                                        if ($record === null) {
                                            return true;
                                        }
                                    }),
                                TextInput::make('dh_cierre')
                                    ->label('DH')
                                    ->inlineLabel(false)
                                    ->prefix('$')
                                    ->disabled() // solo lectura
                                    ->afterStateHydrated(function ($component, $state) use ($resumen) {
                                        $component->state(number_format($resumen->saldo_total,2)); // fija el valor en el state
                                    })
                                    ->reactive() // si otros cálculos dependen de él
                                    ->hidden(fn(?CashBoxOpen $record) => $record === null),



        Placeholder::make('hay_cierre')
                                    ->label('Hay')
                                    ->inlineLabel(false)
                                    ->reactive()
                                    ->content(function (callable $get) {
                                        $hay_cierre = $get('hay_cierre') ?? 0;
                                        return new HtmlString(
                                            '<span style="border-top:#1e2c2e solid 1px; color:green; font-weight:bold; font-size:15px;">
                                            $ ' . number_format($hay_cierre, 2) . '
                                         </span>'
                                        );
                                    })
                                    ->hidden(fn(?CashBoxOpen $record) => $record === null),

                                Placeholder::make('dif_cierre')
                                    ->label('DIF')
                                    ->reactive()
                                    ->inlineLabel(false)
                                    ->content(function (callable $get) {
                                        $dif_cierre = $get('dif_cierre') ?? 0;
                                        return new HtmlString(
                                            '<span style="border-top: #1e2c2e solid 1px; color:green; font-weight:bold; font-size:15px;">
                $ ' . number_format($dif_cierre, 2) . '
             </span>'
                                        );
                                    })
                                    ->hidden(fn(?CashBoxOpen $record) => $record === null),


                                Select::make('close_employee_id')
                                    ->relationship('closeEmployee', 'name', function ($query) {
                                        $whereHouse = auth()->user()->employee->branch_id;
                                        $query->where('branch_id', $whereHouse);
                                    })
                                    ->inlineLabel(false)
                                    ->required()
                                    ->label('Empleado Cierra')
                                    ->hidden(function (?CashBoxOpen $record = null) {
                                        if ($record === null) {
                                            return true;
                                        }
                                    })
                                    ->options(function () {
                                        $whereHouse = auth()->user()->employee->branch_id;
                                        return Employee::where('branch_id', $whereHouse)
                                            ->pluck('name', 'id');
                                    }),
                            ])->columns(6)


                    ])->columns(2)
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cashbox.description')
                    ->placeholder('Caja')
                    ->sortable(),
                TextColumn::make('openEmployee.name')
                    ->label('Aperturó')
                    ->sortable(),
                TextColumn::make('opened_at')
                    ->label('Fecha de apertura')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('open_amount')
                    ->label('Monto Apertura')
                    ->money('USD', true, locale: 'es_US')
                    ->sortable(),
                TextColumn::make('closed_at')
                    ->label('Fecha de cierre')
                    ->placeholder('Sin cerrar')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('saldo_total_operaciones')
                    ->label('Monto Cierre')
                    ->money('USD', true, locale: 'es_US')
                    ->placeholder('Sin cerrar')
                    ->sortable(),
                TextColumn::make('closeEmployee.name')
                    ->label('Cerró')
                    ->placeholder('Sin cerrar')
                    ->sortable(),
                TextColumn::make('status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function ($query) {
                $query->orderby('created_at', 'desc');
            })
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => 'Abierta',
                        'closed' => 'Cerrada',
                    ])
                    ->label('Estado'),
                SelectFilter::make('cash_box_id')
                    ->options(function () {
                        $whereHouse = auth()->user()->employee->branch_id;
                        return CashBox::where('branch_id', $whereHouse)
                            ->get()
                            ->pluck('description', 'id');
                    })
                    ->label('Caja'),
            ])
            ->recordUrl(null)
            ->recordActions([
                EditAction::make()
                    ->label('Cerrar Caja')
                    ->icon('heroicon-o-shield-check')
                    ->hidden(function (CashboxOpen $record) {
                        return $record->status == 'closed';
                    })
                    ->color('danger'),
                Action::make('print')
                    ->label('Imprimir')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->visible(function (CashboxOpen $record) {
                        return $record->status == 'closed';
                    })
                    ->url(fn($record) => route('closeClashBoxPrint', ['idCasboxClose' => $record->id]))
                    ->openUrlInNewTab() // Esto asegura que se abra en una nueva pestaña

            ])
            ->toolbarActions([
                BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => ListCashboxOpens::route('/'),
            'create' => CreateCashboxOpen::route('/create'),
            'edit' => EditCashboxOpen::route('/{record}/edit'),
        ];
    }

    public static function calcularTotal(Get $get, callable $set): void
    {
        // Necesitamos acceso a los valores del formulario


        try {
            $dh = floatval(str_replace(',', '', $get('dh_cierre')));

            $digital = ($get('saldo_tarjeta') ?? 0) + ($get('saldo_cheque') ?? 0) + ($get('saldo_caja_chica') ?? 0);

            $total =
                ($get('cant_cien') ?? 0) * 100 +
                ($get('cant_cincuenta') ?? 0) * 50 +
                ($get('cant_veinte') ?? 0) * 20 +
                ($get('cant_diez') ?? 0) * 10 +
                ($get('cant_cinco') ?? 0) * 5 +
                ($get('cant_uno') ?? 0) * 1 +
                ($get('cant_cora') ?? 0) * 0.25 +
                ($get('cant_cero_diez') ?? 0) * 0.10 +
                ($get('cant_cero_cinco') ?? 0) * 0.05 +
                ($get('cant_cero_cero_uno') ?? 0) * 0.01;
            $set('total_efectivo', number_format($total, 2, '.', ''));
            $hay = $total + $digital;
            $set('hay_cierre', number_format($hay, 2, '.', ''));
            $difencia = $hay - $dh;
//            dd($difencia,'=',);
            $set('dif_cierre', number_format($difencia, 2, '.', ''));
        } catch (Exception $e) {

        }


    }


}
