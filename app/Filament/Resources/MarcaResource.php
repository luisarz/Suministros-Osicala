<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\BulkAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MarcaResource\RelationManagers\ProductosRelationManagerRelationManager;
use App\Filament\Resources\MarcaResource\Pages\ListMarcas;
use App\Filament\Resources\MarcaResource\Pages\CreateMarca;
use App\Filament\Resources\MarcaResource\Pages\EditMarca;
use App\Filament\Resources\MarcaResource\Pages;
use App\Filament\Resources\MarcaResource\RelationManagers;
use App\Models\Marca;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MarcaResource extends Resource
{
    protected static ?string $model = Marca::class;
    protected static ?string $label = 'Marcas';
    protected static ?string $pluralLabel = 'Marcas';
    protected static bool $softDelete = true;
    protected static string | \UnitEnum | null $navigationGroup = 'Almacén';
    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la marca')
                    ->description('Complete los datos de la marca de productos')
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre de la marca')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej: Samsung, Sony, Nike, etc.')
                            ->columnSpan(2),

                        TextInput::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Breve descripción de la marca')
                            ->helperText('Ingrese una descripción breve de la marca')
                            ->columnSpan(2),

                        FileUpload::make('imagen')
                            ->label('Logo de la marca')
                            ->image()
                            ->directory('marcas')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '16:9',
                            ])
                            ->maxSize(2048)
                            ->helperText('Tamaño máximo: 2MB. Formatos: JPG, PNG')
                            ->columnSpan(1),

                        Toggle::make('estado')
                            ->label('Estado activo')
                            ->helperText('Solo las marcas activas estarán disponibles')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->size('sm'),

                ImageColumn::make('imagen')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(url('/images/no-image.png'))
                    ->size(40),

                TextColumn::make('nombre')
                    ->label('Marca')
                    ->sortable()
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 50) {
                            return $state;
                        }
                        return null;
                    }),

                TextColumn::make('productos_count')
                    ->label('Productos')
                    ->counts('productos')
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-cube'),

                BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Activo' : 'Inactivo')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->icons([
                        'heroicon-m-check-circle' => true,
                        'heroicon-m-x-circle' => false,
                    ]),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nombre', 'asc')
            ->filters([
                TernaryFilter::make('estado')
                    ->label('Estado')
                    ->placeholder('Todas las marcas')
                    ->trueLabel('Solo activas')
                    ->falseLabel('Solo inactivas')
                    ->native(false),

                SelectFilter::make('has_products')
                    ->label('Con productos')
                    ->options([
                        'yes' => 'Con productos',
                        'no' => 'Sin productos',
                    ])
                    ->query(fn (Builder $query, array $data) =>
                        match($data['value']) {
                            'yes' => $query->has('productos'),
                            'no' => $query->doesntHave('productos'),
                            default => $query
                        }
                    )
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Editar marca'),

                ReplicateAction::make()
                    ->icon('heroicon-o-document-duplicate')
                    ->color('success')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Duplicar marca')
                    ->excludeAttributes(['imagen'])
                    ->beforeReplicaSaved(function ($record, $replica): void {
                        $replica->nombre = $record->nombre . ' (Copia)';
                    }),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Eliminar marca')
                    ->before(function (DeleteAction $action, Marca $record) {
                        $productosCount = $record->productos()->count();

                        if ($productosCount > 0) {
                            Notification::make()
                                ->warning()
                                ->title('No se puede eliminar')
                                ->body('Esta marca tiene ' . $productosCount . ' producto(s) asociados.')
                                ->persistent()
                                ->send();

                            $action->cancel();
                        }
                    }),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Eliminar marcas seleccionadas')
                        ->before(function (DeleteBulkAction $action, Collection $records) {
                            $withProducts = $records->filter(fn ($r) => $r->productos()->count() > 0);

                            if ($withProducts->count() > 0) {
                                $details = [];

                                foreach ($withProducts as $record) {
                                    $productosCount = $record->productos()->count();
                                    $details[] = "• {$record->nombre}: {$productosCount} producto(s)";
                                }

                                Notification::make()
                                    ->warning()
                                    ->title('Algunas marcas no se pueden eliminar')
                                    ->body('Las siguientes marcas tienen productos asociados:<br>' . implode('<br>', $details))
                                    ->persistent()
                                    ->send();

                                $action->cancel();
                            }
                        }),

                    BulkAction::make('activate')
                        ->label('Activar seleccionadas')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->tooltip('Activar todas las marcas seleccionadas')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['estado' => true])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Marcas activadas')
                                ->body('Las marcas seleccionadas han sido activadas correctamente.')
                        ),

                    BulkAction::make('deactivate')
                        ->label('Desactivar seleccionadas')
                        ->icon('heroicon-o-no-symbol')
                        ->color('warning')
                        ->tooltip('Desactivar todas las marcas seleccionadas')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['estado' => false])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Marcas desactivadas')
                                ->body('Las marcas seleccionadas han sido desactivadas correctamente.')
                        ),
                ]),
            ])
            ->emptyStateHeading('No hay marcas registradas')
            ->emptyStateDescription('Comience creando su primera marca de productos')
            ->emptyStateIcon('heroicon-o-tag')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            ProductosRelationManagerRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMarcas::route('/'),
            'create' => CreateMarca::route('/create'),
            'edit' => EditMarca::route('/{record}/edit'),
        ];
    }
}
