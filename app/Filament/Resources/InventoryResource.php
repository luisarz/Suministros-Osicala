<?php

namespace App\Filament\Resources;

use Auth;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\BulkAction;
use App\Filament\Resources\InventoryResource\RelationManagers\PricesRelationManager;
use App\Filament\Resources\InventoryResource\RelationManagers\GroupingInventoryRelationManager;
use App\Filament\Resources\InventoryResource\Pages\ListInventories;
use App\Filament\Resources\InventoryResource\Pages\CreateInventory;
use App\Filament\Resources\InventoryResource\Pages\EditInventory;
use App\Filament\Exports\InventoryExporter;
use App\Models\Inventory;
use App\Models\Tribute;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class InventoryResource extends Resource
{
    protected static function getWhereHouse(): string
    {
        return Auth::user()->employee->wherehouse->name ?? 'N/A';
    }

    protected static ?string $model = Inventory::class;
    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';
    protected static ?string $label = 'Inventario';
    protected static ?string $pluralLabel = "Lista de inventario";
    protected static ?string $recordTitleAttribute = 'record_title';

    public static function form(Schema $schema): Schema
    {
        $tax = Tribute::find(1)->select('rate', 'is_percentage')->first();
        if (!$tax) {
            $tax = (object)['rate' => 0, 'is_percentage' => false];
        }
        $divider = ($tax->is_percentage) ? 100 : 1;
        $iva = $tax->rate / $divider;

        return $schema
            ->components([
                Section::make('Información del Inventario')
                    ->description('Configure los detalles del inventario del producto')
                    ->icon('heroicon-o-cube-transparent')
                    ->columns(3)
                    ->schema([
                        Select::make('product_id')
                            ->label('Producto')
                            ->required()
                            ->preload()
                            ->columnSpanFull()
                            ->relationship('product', 'name')
                            ->searchable(['name', 'sku'])
                            ->placeholder('Seleccione un producto')
                            ->loadingMessage('Cargando productos...')
                            ->helperText('Busque por nombre o SKU del producto')
                            ->getOptionLabelsUsing(function ($record) {
                                return "{$record->name} (SKU: {$record->sku})";
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                                $branchId = $get('branch_id');
                                if ($state && $branchId) {
                                    $query = Inventory::where('product_id', $state)
                                        ->where('branch_id', $branchId);

                                    if ($record) {
                                        $query->where('id', '!=', $record->id);
                                    }

                                    $exists = $query->exists();

                                    if ($exists) {
                                        Notification::make()
                                            ->danger()
                                            ->title('Inventario Duplicado')
                                            ->body('Este producto ya tiene inventario en la sucursal seleccionada.')
                                            ->persistent()
                                            ->send();
                                    }
                                }
                            }),

                        Select::make('branch_id')
                            ->label('Sucursal')
                            ->placeholder('Seleccione la sucursal')
                            ->relationship('branch', 'name')
                            ->preload()
                            ->searchable(['name'])
                            ->required()
                            ->helperText('Sucursal donde se gestionará el inventario')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                                $productId = $get('product_id');
                                if ($state && $productId) {
                                    $query = Inventory::where('product_id', $productId)
                                        ->where('branch_id', $state);

                                    if ($record) {
                                        $query->where('id', '!=', $record->id);
                                    }

                                    $exists = $query->exists();

                                    if ($exists) {
                                        Notification::make()
                                            ->danger()
                                            ->title('Inventario Duplicado')
                                            ->body('Este producto ya tiene inventario en la sucursal seleccionada.')
                                            ->persistent()
                                            ->send();
                                    }
                                }
                            }),

                        TextInput::make('stock')
                            ->label('Stock Actual')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->placeholder('0')
                            ->helperText('Cantidad actual en inventario'),

                        Hidden::make('stock_actual')
                            ->default(0)
                            ->afterStateHydrated(function (Hidden $component, $state, $record) {
                                if ($record) {
                                    $component->state($record->stock);
                                }
                            }),

                        TextInput::make('stock_min')
                            ->label('Stock Mínimo')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->placeholder('0')
                            ->helperText('Stock mínimo para generar alertas'),

                        TextInput::make('stock_max')
                            ->label('Stock Máximo')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->placeholder('0')
                            ->helperText('Stock máximo permitido'),

                        TextInput::make('cost_without_taxes')
                            ->required()
                            ->prefix('$')
                            ->label('Costo sin IVA')
                            ->numeric()
                            ->inputMode('decimal')
                            ->debounce(500)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) use ($iva) {
                                $costWithoutTaxes = $state ?: 0;
                                $costWithTaxes = number_format($costWithoutTaxes * $iva, 2, '.', '');
                                $costWithTaxes += $costWithoutTaxes;
                                $set('cost_with_taxes', number_format($costWithTaxes, 2, '.', ''));
                            })
                            ->default(0.00)
                            ->placeholder('0.00')
                            ->helperText('Costo del producto sin impuestos'),

                        TextInput::make('cost_with_taxes')
                            ->label('Costo + IVA')
                            ->required()
                            ->readOnly()
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00)
                            ->helperText('Costo calculado automáticamente'),
                    ]),

                Section::make('Configuración')
                    ->description('Opciones de alertas y estado del inventario')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_stock_alert')
                            ->label('Alerta de Stock Mínimo')
                            ->default(true)
                            ->required()
                            ->inline(false)
                            ->helperText('Enviar alertas cuando el stock sea menor al mínimo'),

                        Toggle::make('is_expiration_date')
                            ->label('Tiene Vencimiento')
                            ->default(true)
                            ->required()
                            ->inline(false)
                            ->helperText('Producto sujeto a fecha de vencimiento'),

                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Inventario Activo')
                            ->required()
                            ->inline(false)
                            ->helperText('Solo los inventarios activos están disponibles'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Split::make([
                            Grid::make()
                                ->columns(1)
                                ->schema([
                                    ImageColumn::make('product.images')
                                        ->label('')
                                        ->disk('public')
                                        ->circular()
                                        ->defaultImageUrl(fn ($record) =>
                                            'https://ui-avatars.com/api/?name=' . urlencode($record->product->name) .
                                            '&color=ffffff&background=01d5f2&bold=true&size=120'
                                        )
                                        ->size(120)
                                        ->extraAttributes([
                                            'class' => 'shadow-lg',
                                            'loading' => 'lazy'
                                        ])
                                ])->grow(false),

                            Stack::make([
                                TextColumn::make('product.name')
                                    ->label('Producto')
                                    ->wrap()
                                    ->weight(FontWeight::Medium)
                                    ->size('sm')
                                    ->sortable()
                                    ->searchable()
                                    ->icon('heroicon-o-cube')
                                    ->iconColor('primary')
                                    ->description(fn (Inventory $record): ?string =>
                                        $record->product->aplications
                                            ? 'Aplicaciones: ' . str_replace(';', ' • ', $record->product->aplications)
                                            : null
                                    ),

                                Stack::make([
                                    TextColumn::make('product.sku')
                                        ->label('SKU')
                                        ->copyable()
                                        ->copyMessage('SKU copiado')
                                        ->copyMessageDuration(1500)
                                        ->icon('heroicon-o-qr-code')
                                        ->placeholder('Sin SKU')
                                        ->searchable(),

                                    TextColumn::make('product.bar_code')
                                        ->label('Código de Barras')
                                        ->copyable()
                                        ->copyMessage('Código copiado')
                                        ->icon('heroicon-o-bars-3-bottom-left')
                                        ->placeholder('Sin código')
                                        ->searchable(),
                                ])->space(1),

                                Stack::make([
                                    TextColumn::make('branch.name')
                                        ->label('Sucursal')
                                        ->icon('heroicon-o-building-office-2')
                                        ->badge()
                                        ->color('info')
                                        ->sortable()
                                        ->searchable(),

                                    TextColumn::make('product.marca.nombre')
                                        ->label('Marca')
                                        ->icon('heroicon-o-bookmark')
                                        ->badge()
                                        ->color('primary')
                                        ->searchable(),

                                    TextColumn::make('product.category.name')
                                        ->label('Categoría')
                                        ->icon('heroicon-o-tag')
                                        ->badge()
                                        ->color('gray')
                                        ->searchable(),
                                ])->space(1),

                                Stack::make([
                                    TextColumn::make('stock')
                                        ->label('Stock')
                                        ->icon('heroicon-o-archive-box')
                                        ->badge()
                                        ->formatStateUsing(fn ($record) =>
                                            $record->stock > 0
                                                ? number_format($record->stock, 2)
                                                : 'Sin stock'
                                        )
                                        ->color(function ($record) {
                                            if ($record->stock <= 0) return 'danger';
                                            if ($record->stock < $record->stock_min) return 'danger';
                                            if ($record->stock < ($record->stock_min * 1.5)) return 'warning';
                                            return 'success';
                                        })
                                        ->sortable(),

                                    TextColumn::make('default_price')
                                        ->label('Precio')
                                        ->icon('heroicon-o-currency-dollar')
                                        ->badge()
                                        ->color('success')
                                        ->getStateUsing(function ($record) {
                                            $defaultPrice = collect($record->prices)->firstWhere('is_default', 1);
                                            return $defaultPrice
                                                ? '$' . number_format($defaultPrice['price'], 2)
                                                : 'Sin precio';
                                        }),
                                ])->space(1),

                            ])->extraAttributes([
                                'class' => 'space-y-2 p-4'
                            ])->grow(),
                        ])
                    ]),
            ])
            ->contentGrid([
                'md' => 2,
                'lg' => 3,
                'xl' => 3,
            ])
            ->paginationPageOptions([9, 18, 30, 60])
            ->striped()
            ->filters([
                Filter::make('Buscar por nombre')
                    ->schema([
                        TextInput::make('name')
                            ->label('Producto')
                            ->placeholder('Buscar producto...')
                            ->prefixIcon('heroicon-o-magnifying-glass'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['name'], fn ($q, $nombre) =>
                            $q->whereHas('product', fn ($subQuery) =>
                            $subQuery->where('name', 'like', "%{$nombre}%")
                            )
                            );
                    }),

                SelectFilter::make('branch_id')
                    ->label('Sucursal')
                    ->searchable()
                    ->preload()
                    ->relationship('branch', 'name')
                    ->placeholder('Todas las sucursales'),

                SelectFilter::make('product.marca')
                    ->label('Marca')
                    ->searchable()
                    ->preload()
                    ->relationship('product.marca', 'nombre')
                    ->placeholder('Todas las marcas'),

                SelectFilter::make('product.category')
                    ->label('Categoría')
                    ->searchable()
                    ->preload()
                    ->relationship('product.category', 'name')
                    ->placeholder('Todas las categorías'),

                SelectFilter::make('is_active')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activos',
                        '0' => 'Inactivos',
                    ])
                    ->placeholder('Todos los estados'),

                Filter::make('stock_critico')
                    ->label('Stock Crítico/Bajo')
                    ->toggle()
                    ->query(fn (Builder $query) =>
                        $query->where(function ($q) {
                            $q->whereColumn('stock', '<', 'stock_min')
                              ->orWhereColumn('stock', '<', \DB::raw('stock_min * 1.5'));
                        })
                    ),

                Filter::make('sin_precio')
                    ->label('Sin Precio Asignado')
                    ->toggle()
                    ->query(fn (Builder $query) =>
                        $query->whereDoesntHave('prices', fn ($q) =>
                            $q->where('is_default', 1)
                        )
                    ),

                TrashedFilter::make()
                    ->label('Eliminados'),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->color('primary')
                    ->tooltip('Editar inventario'),

                ReplicateAction::make()
                    ->icon('heroicon-o-document-duplicate')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->color('success')
                    ->tooltip('Replicar a otra sucursal')
                    ->modalHeading('Replicar Inventario a Otra Sucursal')
                    ->modalSubmitActionLabel('Replicar')
                    ->schema([
                        Select::make('branch_did')
                            ->relationship('branch', 'name')
                            ->label('Sucursal Destino')
                            ->required()
                            ->placeholder('Seleccione la sucursal destino'),
                    ])
                    ->beforeReplicaSaved(function (Inventory $record, \Filament\Actions\Action $action, $replica, array $data): void {
                        try {
                            $existencia = Inventory::withTrashed()
                                ->where('product_id', $record->product_id)
                                ->where('branch_id', $data['branch_did'])
                                ->first();
                            if ($existencia) {
                                if ($existencia->trashed()) {
                                    Notification::make('Inventario Eliminado')
                                        ->title('Replicar Inventario')
                                        ->danger()
                                        ->body('El inventario ya existe en la sucursal destino, pero está eliminado. Restáurelo para poder replicarlo.')
                                        ->send();
                                    $action->halt();
                                } else {
                                    Notification::make('Registro Duplicado')
                                        ->danger()
                                        ->body('Ya existe un inventario del producto ' . $record->product->name . ' en la sucursal seleccionada.')
                                        ->send();
                                    $action->halt();
                                }
                            }
                        } catch (Exception $e) {
                            $action->halt();
                        }
                    })
                    ->successNotificationTitle('Inventario replicado correctamente'),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->color('danger')
                    ->tooltip('Eliminar inventario')
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar Inventario')
                    ->modalDescription('¿Está seguro de eliminar este inventario? Podrá recuperarlo después.')
                    ->modalSubmitActionLabel('Sí, eliminar')
                    ->before(function (DeleteAction $action, Inventory $record) {
                        if ($record->stock > 0) {
                            Notification::make()
                                ->warning()
                                ->title('No se puede eliminar')
                                ->body('Este inventario tiene ' . number_format($record->stock, 2) . ' unidades en stock. Debe ajustar el stock a 0 antes de eliminarlo.')
                                ->persistent()
                                ->send();

                            $action->cancel();
                        }
                    })
                    ->successNotificationTitle('Inventario eliminado correctamente'),

                RestoreAction::make()
                    ->icon('heroicon-o-arrow-path')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->color('success')
                    ->tooltip('Restaurar inventario')
                    ->successNotificationTitle('Inventario restaurado correctamente'),

                ForceDeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->color('danger')
                    ->tooltip('Eliminar permanentemente')
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar Permanentemente')
                    ->modalDescription('¿Está seguro? Esta acción NO se puede deshacer.')
                    ->modalSubmitActionLabel('Sí, eliminar permanentemente')
                    ->successNotificationTitle('Inventario eliminado permanentemente'),
            ], position: RecordActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Activar seleccionados')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Activar Inventarios')
                        ->modalDescription(fn (Collection $records) =>
                            'Se activarán ' . $records->count() . ' inventario(s) seleccionado(s).'
                        )
                        ->modalSubmitActionLabel('Sí, activar')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['is_active' => true])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Inventarios activados correctamente'),

                    BulkAction::make('deactivate')
                        ->label('Desactivar seleccionados')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Desactivar Inventarios')
                        ->modalDescription(fn (Collection $records) =>
                            'Se desactivarán ' . $records->count() . ' inventario(s) seleccionado(s).'
                        )
                        ->modalSubmitActionLabel('Sí, desactivar')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['is_active' => false])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Inventarios desactivados correctamente'),

                    BulkAction::make('enable_stock_alert')
                        ->label('Activar alerta de stock')
                        ->icon('heroicon-o-bell-alert')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Activar Alerta de Stock')
                        ->modalDescription(fn (Collection $records) =>
                            'Se activará la alerta de stock mínimo en ' . $records->count() . ' inventario(s).'
                        )
                        ->modalSubmitActionLabel('Sí, activar alertas')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['is_stock_alert' => true])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Alertas de stock activadas correctamente'),

                    BulkAction::make('disable_stock_alert')
                        ->label('Desactivar alerta de stock')
                        ->icon('heroicon-o-bell-slash')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->modalHeading('Desactivar Alerta de Stock')
                        ->modalDescription(fn (Collection $records) =>
                            'Se desactivará la alerta de stock mínimo en ' . $records->count() . ' inventario(s).'
                        )
                        ->modalSubmitActionLabel('Sí, desactivar alertas')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['is_stock_alert' => false])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Alertas de stock desactivadas correctamente'),

                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Inventarios Seleccionados')
                        ->modalDescription(fn (Collection $records) =>
                            '¿Está seguro de eliminar ' . $records->count() . ' inventario(s)? Podrá recuperarlos después.'
                        )
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->before(function (DeleteBulkAction $action, Collection $records) {
                            $withStock = $records->filter(fn ($r) => $r->stock > 0);

                            if ($withStock->count() > 0) {
                                $details = [];

                                foreach ($withStock as $record) {
                                    $details[] = "• {$record->product->name} (Sucursal: {$record->branch->name}): " . number_format($record->stock, 2) . " unidades";
                                }

                                Notification::make()
                                    ->warning()
                                    ->title('Algunos inventarios no se pueden eliminar')
                                    ->body('Los siguientes inventarios tienen stock y deben ser ajustados a 0 primero:<br>' . implode('<br>', $details))
                                    ->persistent()
                                    ->send();

                                $action->cancel();
                            }
                        })
                        ->successNotificationTitle('Inventarios eliminados correctamente'),

                    RestoreBulkAction::make()
                        ->icon('heroicon-o-arrow-path')
                        ->successNotificationTitle('Inventarios restaurados correctamente'),

                    ForceDeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Permanentemente')
                        ->modalDescription(fn (Collection $records) =>
                            '¿Está seguro de eliminar permanentemente ' . $records->count() . ' inventario(s)? Esta acción NO se puede deshacer.'
                        )
                        ->modalSubmitActionLabel('Sí, eliminar permanentemente')
                        ->successNotificationTitle('Inventarios eliminados permanentemente'),

                    ExportAction::make()
                        ->icon('heroicon-o-arrow-down-tray')
                        ->exporter(InventoryExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                            ExportFormat::Csv,
                        ])
                        ->label('Exportar a Excel'),
                ]),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->recordUrl(null)
            ->emptyStateHeading('No hay inventarios registrados')
            ->emptyStateDescription('Cree su primer inventario para comenzar.')
            ->emptyStateIcon('heroicon-o-cube-transparent');
    }

    public static function getRelations(): array
    {
        return [
            PricesRelationManager::class,
            GroupingInventoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventories::route('/'),
            'create' => CreateInventory::route('/create'),
            'edit' => EditInventory::route('/{record}/edit'),
        ];
    }
}
