<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $label = 'Empleados';
    protected static ?string $navigationGroup = 'Recursos Humanos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Empleado')
                    ->columns(1)
                    ->tabs([
                        Tabs\tab::make('Datos Personales')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Card::make('Datos Laborales')
                                    ->description('Información Sucursal y Cargo')
                                    ->icon('heroicon-o-briefcase')
                                    ->compact()
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\Select::make('branch_id')
                                            ->label('Sucursal')
                                            ->relationship('wherehouse', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->required(),
                                        Forms\Components\Select::make('job_title_id')
                                            ->label('Cargo')
                                            ->relationship('job', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                    ]),


                                Forms\Components\Card::make('Datos Personales')
                                    ->description('Datos Personales')
                                    ->icon('heroicon-o-user')
                                    ->compact()
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nombre')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('lastname')
                                            ->label('Apellido')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Select::make('gender')
                                            ->label('Género')
                                            ->options([
                                                'M' => 'Masculino',
                                                'F' => 'Femenino',
                                            ])
                                            ->required(),
                                        Forms\Components\FileUpload::make('photo')
//                                            ->avatar()
                                            ->directory('emplyees'),
//                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('dui')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('nit')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('phone')
                                            ->tel()
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Card::make()
                                    ->description('Datos de contacto')
                                    ->icon('heroicon-o-calendar')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\TextInput::make('department_id')
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\TextInput::make('municipility_id')
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\TextInput::make('distrito_id')
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\TextInput::make('address')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Card::make('Datos Familiares')
                                    ->description('Datos Familiares')
                                    ->icon('heroicon-o-briefcase')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\DatePicker::make('birthdate'),

                                        Forms\Components\TextInput::make('marital_status')
                                            ->required(),
                                        Forms\Components\TextInput::make('marital_name')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('marital_phone')
                                            ->tel()
                                            ->maxLength(255)
                                            ->default(null),
                                    ])
                            ]),
                        Tabs\Tab::make('Datos Laborales')
                            ->icon('heroicon-o-briefcase')
                            ->schema([

                            ]),
                        Tabs\Tab::make('Datos de Contacto')
                            ->icon('heroicon-o-phone')
                            ->columns(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_comisioned')
                                    ->required(),
                                Forms\Components\TextInput::make('comision')
                                    ->numeric()
                                    ->default(null),
                                Forms\Components\Toggle::make('is_active')
                                    ->required(),
                            ]),
                    ])->columnSpanFull(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthdate')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('marital_status'),
                Tables\Columns\TextColumn::make('marital_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marital_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dui')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('municipility_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('distrito_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_title_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_comisioned')
                    ->boolean(),
                Tables\Columns\TextColumn::make('comision')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
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
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
