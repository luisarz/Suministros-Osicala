<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $label = 'Categorías';
    protected static ?string $pluralLabel = 'Categorías';
    protected static ?bool $softDelete = true;
    protected static string | \UnitEnum | null $navigationGroup = 'Almacén';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la categoría')
                    ->description('Complete los datos de la categoría de productos')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de la categoría')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej: Electrónica, Alimentos, etc.')
                            ->columnSpan(2),

                        Select::make('parent_id')
                            ->relationship(
                                'category',
                                'name',
                                fn (Builder $query, $record) =>
                                    $record
                                        ? $query->where('id', '!=', $record->id)
                                        : $query
                            )
                            ->nullable()
                            ->placeholder('Sin categoría padre (categoría principal)')
                            ->preload()
                            ->searchable()
                            ->label('Categoría padre')
                            ->helperText('Seleccione una categoría padre para crear una subcategoría')
                            ->native(false),

                        TextInput::make('commission_percentage')
                            ->label('Porcentaje de comisión')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('%')
                            ->default(0)
                            ->helperText('Comisión aplicable a las ventas de productos en esta categoría'),

                        Toggle::make('is_active')
                            ->label('Estado activo')
                            ->helperText('Solo las categorías activas estarán disponibles')
                            ->default(true)
                            ->inline(false),
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

                TextColumn::make('name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Category $record): ?string =>
                        $record->parent ? "↳ Subcategoría de: {$record->parent->name}" : null
                    )
                    ->weight('medium'),

                TextColumn::make('category.name')
                    ->label('Categoría padre')
                    ->placeholder('— Principal —')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('products_count')
                    ->label('Productos')
                    ->counts('products')
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-cube'),

                TextColumn::make('children_count')
                    ->label('Subcategorías')
                    ->counts('children')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-folder'),

                BadgeColumn::make('is_active')
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

                TextColumn::make('commission_percentage')
                    ->label('Comisión')
                    ->suffix('%')
                    ->sortable()
                    ->alignEnd()
                    ->color('primary')
                    ->weight('medium'),

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
            ->defaultSort('name', 'asc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todas las categorías')
                    ->trueLabel('Solo activas')
                    ->falseLabel('Solo inactivas')
                    ->native(false),

                SelectFilter::make('parent_id')
                    ->label('Categoría padre')
                    ->relationship('parent', 'name')
                    ->placeholder('Todas')
                    ->preload()
                    ->searchable()
                    ->native(false),

                SelectFilter::make('has_products')
                    ->label('Con productos')
                    ->options([
                        'yes' => 'Con productos',
                        'no' => 'Sin productos',
                    ])
                    ->query(fn (Builder $query, array $data) =>
                        match($data['value']) {
                            'yes' => $query->has('products'),
                            'no' => $query->doesntHave('products'),
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
                    ->tooltip('Editar categoría'),

                ReplicateAction::make()
                    ->icon('heroicon-o-document-duplicate')
                    ->color('success')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Duplicar categoría')
                    ->excludeAttributes(['parent_id'])
                    ->beforeReplicaSaved(function ($record, $replica): void {
                        $replica->name = $record->name . ' (Copia)';
                    }),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Eliminar categoría')
                    ->before(function (DeleteAction $action, Category $record) {
                        $productsCount = $record->products()->count();
                        $subcategoriesCount = $record->children()->count();

                        if ($productsCount > 0 || $subcategoriesCount > 0) {
                            $messages = [];

                            if ($productsCount > 0) {
                                $messages[] = $productsCount . ' producto(s)';
                            }

                            if ($subcategoriesCount > 0) {
                                $messages[] = $subcategoriesCount . ' subcategoría(s)';
                            }

                            Notification::make()
                                ->warning()
                                ->title('No se puede eliminar')
                                ->body('Esta categoría tiene ' . implode(' y ', $messages) . ' asociados.')
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
                        ->tooltip('Eliminar categorías seleccionadas')
                        ->before(function (DeleteBulkAction $action, Collection $records) {
                            $withRelations = $records->filter(function ($record) {
                                return $record->products()->count() > 0 || $record->children()->count() > 0;
                            });

                            if ($withRelations->count() > 0) {
                                $details = [];

                                foreach ($withRelations as $record) {
                                    $info = [];
                                    $productsCount = $record->products()->count();
                                    $subcategoriesCount = $record->children()->count();

                                    if ($productsCount > 0) {
                                        $info[] = $productsCount . ' producto(s)';
                                    }
                                    if ($subcategoriesCount > 0) {
                                        $info[] = $subcategoriesCount . ' subcategoría(s)';
                                    }

                                    $details[] = "• {$record->name}: " . implode(' y ', $info);
                                }

                                Notification::make()
                                    ->warning()
                                    ->title('Algunas categorías no se pueden eliminar')
                                    ->body('Las siguientes categorías tienen registros asociados:<br>' . implode('<br>', $details))
                                    ->persistent()
                                    ->send();

                                $action->cancel();
                            }
                        }),

                    BulkAction::make('activate')
                        ->label('Activar seleccionadas')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->tooltip('Activar todas las categorías seleccionadas')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['is_active' => true])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Categorías activadas')
                                ->body('Las categorías seleccionadas han sido activadas correctamente.')
                        ),

                    BulkAction::make('deactivate')
                        ->label('Desactivar seleccionadas')
                        ->icon('heroicon-o-no-symbol')
                        ->color('warning')
                        ->tooltip('Desactivar todas las categorías seleccionadas')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['is_active' => false])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Categorías desactivadas')
                                ->body('Las categorías seleccionadas han sido desactivadas correctamente.')
                        ),
                ]),
            ])
            ->emptyStateHeading('No hay categorías registradas')
            ->emptyStateDescription('Comience creando su primera categoría de productos')
            ->emptyStateIcon('heroicon-o-folder')
            ->striped();
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
            'index' => ListCategories::route('/'),
        ];
    }
}
