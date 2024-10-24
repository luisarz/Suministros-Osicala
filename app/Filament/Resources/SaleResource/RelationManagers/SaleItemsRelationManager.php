<?php

namespace App\Filament\Resources\SaleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class SaleItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'saleDetails';
    protected static ?string $label = 'Prodúctos agregados';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('inventory_id')
                    ->relationship('inventory', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->recordTitleAttribute('Sales Item')
            ->columns([
                Tables\Columns\TextColumn::make('Sales Item'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
