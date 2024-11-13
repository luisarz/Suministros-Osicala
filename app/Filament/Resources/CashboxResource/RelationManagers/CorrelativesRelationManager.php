<?php

namespace App\Filament\Resources\CashBoxResource\RelationManagers;

use App\Models\PurchaseItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Component;

class CorrelativesRelationManager extends RelationManager
{
    protected static string $relationship = 'correlatives';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cash_box_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('document_type_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('serie')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('start_number')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('end_number')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('current_number')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Toggle::make('is_active'),
            ]);
    }

    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cash_box_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('serie')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('5xl')
                    ->modalHeading('Agregar Tiraje')
                    ->label('Agregar Tiraje')
                    ,
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

}
