<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CashboxOpenResource\Pages;
use App\Filament\Resources\CashboxOpenResource\RelationManagers;
use App\Models\CashboxOpen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashboxOpenResource extends Resource
{
    protected static ?string $model = CashboxOpen::class;

//    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static ?string $label = "Apertura de Cajas";
    public static ?string $navigationGroup = 'Facturación';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->compact()
                    ->columnSpan(2)
                    ->label('Administracion Aperturas de caja')
                    ->schema([
                        Forms\Components\Select::make('cashbox_id')
                            ->relationship('cashbox', 'description')
                            ->options(function () {
                                $whereHouse = auth()->user()->employee->branch_id;
                                $cashBoxes = \App\Models\CashBox::where('branch_id', $whereHouse)
                                    ->where('is_open', '0')
                                    ->get()
                                    ->pluck('description', 'id');

                                return $cashBoxes;
                            })
                            ->visible(function (CashBoxOpen $record = null) {
                                if ($record === null) {
                                    return true;
                                }
                            })
                            ->label('Caja')
                            ->preload()
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::class::make('open_employee_id')
                            ->relationship('openEmployee', 'name', function ($query) {
                                $whereHouse = auth()->user()->employee->branch_id;
                                $query->where('branch_id', $whereHouse);
                            })
                            ->visible(function (CashBoxOpen $record = null) {
                                if ($record === null) {
                                    return true;
                                }
                            })
                            ->label('Empleado Apertura')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DateTimePicker::make('opened_at')
                            ->label('Fecha de apertura')
                            ->inlineLabel(true)
                            ->default(now())
                            ->visible(function (CashBoxOpen $record = null) {
                                if ($record === null) {
                                    return true;
                                }
                            })
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Monto Apertura')
                            ->required()
                            ->numeric()
                            ->readOnly(fn($record) => $record && $record->status === 'closed'),

                        Forms\Components\DateTimePicker::make('closed_at')
                            ->label('Fecha de cierre')
                            ->default(now())
                            ->hidden(function (CashBoxOpen $record = null) {
                                if ($record === null) {
                                    return true;
                                }
                            })
                            ->inlineLabel(true),
                        Forms\Components\TextInput::make('closed_amount')
                            ->hidden(function (CashBoxOpen $record = null) {
                                if ($record === null) {
                                    return true;
                                }
                            }),
                        Forms\Components\Select::make('close_employee_id')
                            ->relationship('closeEmployee', 'name', function ($query) {
                                $whereHouse = auth()->user()->employee->branch_id;
                                $query->where('branch_id', $whereHouse);
                            })
                            ->label('Empleado Cierra')
                            ->hidden(function (CashBoxOpen $record = null) {
                                if ($record === null) {
                                    return true;
                                }
                            })
                            ->options(function () {
                                $whereHouse = auth()->user()->employee->branch_id;
                                return \App\Models\Employee::where('branch_id', $whereHouse)
                                    ->pluck('name', 'id');
                            }),

                    ])->columns(1)->columnSpan(5)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cashbox.description')
                    ->placeholder('Caja')
                    ->sortable(),
                Tables\Columns\TextColumn::make('openEmployee.name')
                    ->label('Aperturó')
                    ->sortable(),
                Tables\Columns\TextColumn::make('opened_at')
                    ->label('Fecha de apertura')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto Apertura')
                    ->money('USD', true, locale: 'es_US')
                    ->sortable(),
                Tables\Columns\TextColumn::make('closed_at')
                    ->label('Fecha de cierre')
                    ->placeholder('Sin cerrar')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('closed_amount')
                    ->label('Monto Cierre')
                    ->money('USD', true, locale: 'es_US')
                    ->placeholder('Sin cerrar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('closeEmployee.name')
                    ->placeholder('Sin cerrar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Abierta',
                        'closed' => 'Cerrada',
                    ])
                    ->label('Estado'),
                Tables\Filters\SelectFilter::make('cash_box_id')
                    ->options(function () {
                        $whereHouse = auth()->user()->employee->branch_id;
                        $cashBoxes = \App\Models\CashBox::where('branch_id', $whereHouse)
                            ->get()
                            ->pluck('description', 'id');
                        return $cashBoxes;
                    })
                    ->label('Caja'),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Cerrar Caja')
                    ->icon('heroicon-o-shield-check')
                    ->hidden(function (CashboxOpen $record) {
                        return $record->status == 'closed';
                    })
                    ->color('danger'),
                Tables\Actions\Action::make('print')
                    ->label('Imprimir')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->visible(function (CashboxOpen $record) {
                        return $record->status == 'closed';
                    })

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCashboxOpens::route('/'),
            'create' => Pages\CreateCashboxOpen::route('/create'),
            'edit' => Pages\EditCashboxOpen::route('/{record}/edit'),
        ];
    }

    public function beforeCreate()
    {
        dd($record);

    }
}
