<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerDocumentTypeResource\Pages;
use App\Filament\Resources\CustomerDocumentTypeResource\RelationManagers;
use App\Models\CustomerDocumentType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerDocumentTypeResource extends Resource
{
    protected static ?string $model = CustomerDocumentType::class;
    protected static ?string $label = 'Tipo  Documento Cliente';
    protected static ?string $navigationGroup = 'Catálogos Hacienda';
    protected static ?int $navigationSort = 100;

//    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de Tipo de Documento del Cliente')
                ->columns(3)
                    ->compact()
                    ->schema([
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(5),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(150),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->required(),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
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
            'index' => Pages\ListCustomerDocumentTypes::route('/'),
            'create' => Pages\CreateCustomerDocumentType::route('/create'),
            'edit' => Pages\EditCustomerDocumentType::route('/{record}/edit'),
        ];
    }
}
