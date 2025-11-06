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
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\BulkAction;
use Filament\Tables;
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
    protected static ?string $label = 'Marca';
    protected static ?string $pluralLabel = 'Marcas';
    protected static bool $softDelete = true;
    protected static string | \UnitEnum | null $navigationGroup = 'Almacén';
    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->description('Datos básicos de la marca')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre de la Marca')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej: Samsung, Sony, Nike, etc.')
                            ->helperText('Nombre oficial de la marca')
                            ->columnSpanFull(),

                        TextInput::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Breve descripción de la marca')
                            ->helperText('Información adicional sobre la marca y sus productos')
                            ->columnSpanFull(),
                    ])->columns(1),

                Section::make('Logo de la Marca')
                    ->description('Imagen representativa de la marca')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('imagen')
                            ->label('')
                            ->image()
                            ->disk('public')
                            ->directory('marcas')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                                '16:9',
                            ])
                            ->maxSize(2048)
                            ->openable()
                            ->downloadable()
                            ->imagePreviewHeight('250')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left')
                            ->helperText('📸 Tamaño máximo: 2MB | Formatos: JPG, PNG, WEBP | Recomendado: 512x512px')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Section::make('Configuración')
                    ->description('Estado de la marca')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Toggle::make('estado')
                            ->label('Marca Activa')
                            ->helperText('Solo las marcas activas estarán disponibles para productos')
                            ->default(true)
                            ->inline(false),
                    ]),
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
                    ->disk('public')
                    ->circular()
                    ->size(60)
                    ->defaultImageUrl(function ($record) {
                        // Genera un avatar bonito con las iniciales de la marca
                        $nombre = urlencode($record->nombre);
                        $iniciales = urlencode(substr($record->nombre, 0, 2));
                        // Colores aleatorios pero consistentes basados en el nombre
                        $colors = ['3b82f6', '8b5cf6', 'ec4899', 'f97316', '10b981', '06b6d4', 'f59e0b', 'ef4444'];
                        $colorIndex = abs(crc32($record->nombre)) % count($colors);
                        $bgColor = $colors[$colorIndex];

                        return "https://ui-avatars.com/api/?name={$iniciales}&color=ffffff&background={$bgColor}&bold=true&size=128";
                    })
                    ->extraImgAttributes([
                        'class' => 'object-cover',
                    ])
                    ->tooltip(fn (Marca $record): string => $record->nombre),

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
            ->actionsPosition(Tables\Enums\ActionsPosition::BeforeColumns)
            ->recordActions([
                ViewAction::make()
                    ->label('')
                    ->icon('heroicon-m-eye')
                    ->iconSize(IconSize::Medium)
                    ->tooltip('Ver'),

                EditAction::make()
                    ->label('')
                    ->icon('heroicon-m-pencil-square')
                    ->iconSize(IconSize::Medium)
                    ->color('warning')
                    ->tooltip('Modificar'),

                ReplicateAction::make()
                    ->label('')
                    ->icon('heroicon-m-document-duplicate')
                    ->iconSize(IconSize::Medium)
                    ->color('info')
                    ->tooltip('Duplicar')
                    ->excludeAttributes(['imagen'])
                    ->beforeReplicaSaved(function ($record, $replica): void {
                        $replica->nombre = $record->nombre . ' (Copia)';
                    }),

                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-m-trash')
                    ->iconSize(IconSize::Medium)
                    ->color('danger')
                    ->tooltip('Eliminar')
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

                RestoreAction::make()
                    ->label('')
                    ->icon('heroicon-m-arrow-path')
                    ->iconSize(IconSize::Medium)
                    ->color('success')
                    ->tooltip('Restaurar'),
            ])
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
            ->striped()
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession();
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
