<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid as FormGrid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use App\Models\Category;
use App\Models\Marca;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $label = 'Producto';
    protected static ?string $pluralLabel = 'Productos';
    protected static string | \UnitEnum | null $navigationGroup = 'Almacén';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 3;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'bar_code'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Producto' => $record->name,
            'SKU' => $record->sku,
            'Código de Barra' => $record->bar_code,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Producto')
                    ->description('Configure los detalles del producto o servicio')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        FormGrid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre del Producto')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Aceite para motor 20W-50')
                                    ->helperText('Nombre descriptivo del producto o servicio')
                                    ->columnSpan(2),

                                TextInput::make('aplications')
                                    ->label('Aplicaciones')
                                    ->placeholder('Ej: Motor; Transmisión; Hidráulico')
                                    ->helperText('Separar con punto y coma (;)')
                                    ->columnSpan(2),
                            ]),

                        FormGrid::make(2)
                            ->schema([
                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Ej: ACE-20W50-001')
                                    ->helperText('Código único del producto (Stock Keeping Unit)')
                                    ->columnSpan(1),

                                TextInput::make('bar_code')
                                    ->label('Código de Barras')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Ej: 7501234567890')
                                    ->helperText('Código de barras único para escaneo')
                                    ->columnSpan(1),
                            ]),

                        FormGrid::make(3)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Categoría')
                                    ->relationship(
                                        name: 'category',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn ($query) => $query->whereNotNull('parent_id')
                                    )
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nombre de categoría')
                                            ->required(),
                                        Select::make('parent_id')
                                            ->label('Categoría padre')
                                            ->relationship('parent', 'name')
                                            ->required()
                                    ])
                                    ->placeholder('Seleccione una categoría')
                                    ->helperText('Línea o categoría del producto')
                                    ->columnSpan(1),

                                Select::make('marca_id')
                                    ->label('Marca')
                                    ->preload()
                                    ->searchable()
                                    ->relationship('marca', 'nombre')
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->label('Nombre de marca')
                                            ->required(),
                                        FileUpload::make('logo')
                                            ->label('Logo')
                                            ->image()
                                            ->directory('marcas')
                                    ])
                                    ->placeholder('Seleccione una marca')
                                    ->helperText('Marca o fabricante')
                                    ->columnSpan(1),

                                Select::make('unit_measurement_id')
                                    ->label('Unidad de Medida')
                                    ->preload()
                                    ->searchable()
                                    ->relationship('unitMeasurement', 'description')
                                    ->required()
                                    ->placeholder('Seleccione unidad')
                                    ->helperText('Presentación o unidad de venta')
                                    ->columnSpan(1),
                            ]),
                    ]),

                Section::make('Configuración del Producto')
                    ->description('Configure las propiedades y características')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        FormGrid::make(4)
                            ->schema([
                                Toggle::make('is_service')
                                    ->label('Es un Servicio')
                                    ->default(false)
                                    ->inline(false)
                                    ->helperText('Marcar si es servicio, desmarcar si es producto físico'),

                                Toggle::make('is_active')
                                    ->label('Producto Activo')
                                    ->default(true)
                                    ->inline(false)
                                    ->helperText('Solo productos activos están disponibles'),

                                Toggle::make('is_grouped')
                                    ->label('Producto Compuesto')
                                    ->default(false)
                                    ->inline(false)
                                    ->helperText('Producto compuesto por varios artículos'),

                                Toggle::make('is_taxed')
                                    ->label('Producto Gravado')
                                    ->default(true)
                                    ->inline(false)
                                    ->helperText('Aplica IVA y otros impuestos'),
                            ]),
                    ]),

                Section::make('Imágenes del Producto')
                    ->description('Cargue imágenes del producto (máximo 2MB por imagen)')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Imágenes')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('products')
                            ->image()
                            ->multiple()
                            ->maxFiles(5)
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                                '16:9',
                            ])
                            ->maxSize(2048)
                            ->reorderable()
                            ->openable()
                            ->downloadable()
                            ->helperText('Puede cargar hasta 5 imágenes. Recomendado: 800x800px')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->size('sm'),

                ImageColumn::make('images')
                    ->label('Imagen')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn ($record) =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->name) .
                        '&color=ffffff&background=01d5f2&bold=true&size=128'
                    )
                    ->size(40)
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-o-cube')
                    ->iconColor('primary')
                    ->description(fn (Product $record): ?string =>
                        $record->aplications
                            ? 'Aplicaciones: ' . str_replace(';', ' • ', $record->aplications)
                            : null
                    ),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->icon('heroicon-m-qr-code')
                    ->copyable()
                    ->copyMessage('SKU copiado')
                    ->copyMessageDuration(1500)
                    ->placeholder('Sin SKU')
                    ->toggleable()
                    ->size('sm'),

                TextColumn::make('bar_code')
                    ->label('Código de Barras')
                    ->searchable()
                    ->icon('heroicon-m-bars-3-bottom-left')
                    ->copyable()
                    ->copyMessage('Código copiado')
                    ->placeholder('Sin código')
                    ->toggleable()
                    ->size('sm'),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('unitMeasurement.description')
                    ->label('Unidad')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('inventories_count')
                    ->label('Stock')
                    ->counts('inventories')
                    ->formatStateUsing(fn ($state) =>
                        $state > 0
                            ? "{$state} " . ($state === 1 ? 'sucursal' : 'sucursales')
                            : 'Sin stock'
                    )
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state === 0 => 'danger',
                        $state < 3 => 'warning',
                        default => 'success',
                    })
                    ->sortable()
                    ->alignCenter(),

                IconColumn::make('is_service')
                    ->label('Tipo')
                    ->boolean()
                    ->trueIcon('heroicon-o-wrench-screwdriver')
                    ->falseIcon('heroicon-o-cube')
                    ->trueColor('info')
                    ->falseColor('gray')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Fecha Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->size('sm'),

                TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->size('sm'),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->searchable()
                    ->preload()
                    ->relationship('category', 'name')
                    ->placeholder('Todas las categorías'),

                SelectFilter::make('marca_id')
                    ->label('Marca')
                    ->searchable()
                    ->preload()
                    ->relationship('marca', 'nombre')
                    ->placeholder('Todas las marcas'),

                TernaryFilter::make('is_service')
                    ->label('Tipo')
                    ->placeholder('Todos')
                    ->trueLabel('Servicios')
                    ->falseLabel('Productos'),

                TernaryFilter::make('has_image')
                    ->label('Con Imagen')
                    ->placeholder('Todos')
                    ->trueLabel('Con imagen')
                    ->falseLabel('Sin imagen')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('images'),
                        false: fn ($query) => $query->whereNull('images'),
                    ),

                TernaryFilter::make('has_inventory')
                    ->label('Con Inventario')
                    ->placeholder('Todos')
                    ->trueLabel('Con inventario')
                    ->falseLabel('Sin inventario')
                    ->queries(
                        true: fn ($query) => $query->has('inventories'),
                        false: fn ($query) => $query->doesntHave('inventories'),
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
                    ->tooltip('Editar producto')
                    ->modalHeading('Editar Producto')
                    ->modalSubmitActionLabel('Guardar cambios')
                    ->successNotificationTitle('Producto actualizado correctamente'),

                ReplicateAction::make()
                    ->icon('heroicon-o-document-duplicate')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->color('success')
                    ->tooltip('Duplicar producto')
                    ->modalHeading('Duplicar Producto')
                    ->modalSubmitActionLabel('Duplicar')
                    ->excludeAttributes(['name', 'sku', 'bar_code'])
                    ->successNotificationTitle('Producto duplicado correctamente'),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->color('danger')
                    ->tooltip('Eliminar producto')
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar Producto')
                    ->modalDescription('¿Está seguro de eliminar este producto? Podrá recuperarlo después.')
                    ->modalSubmitActionLabel('Sí, eliminar')
                    ->modalCancelActionLabel('Cancelar')
                    ->successNotificationTitle('Producto eliminado correctamente'),

                RestoreAction::make()
                    ->icon('heroicon-o-arrow-path')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->color('success')
                    ->tooltip('Restaurar producto')
                    ->successNotificationTitle('Producto restaurado correctamente'),

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
                    ->successNotificationTitle('Producto eliminado permanentemente'),
            ], position: RecordActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Productos Seleccionados')
                        ->modalDescription(fn (Collection $records) =>
                            '¿Está seguro de eliminar ' . $records->count() . ' producto(s)? Podrá recuperarlos después.'
                        )
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->successNotificationTitle('Productos eliminados correctamente'),

                    RestoreBulkAction::make()
                        ->icon('heroicon-o-arrow-path')
                        ->successNotificationTitle('Productos restaurados correctamente'),

                    ForceDeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Permanentemente')
                        ->modalDescription(fn (Collection $records) =>
                            '¿Está seguro de eliminar permanentemente ' . $records->count() . ' producto(s)? Esta acción NO se puede deshacer.'
                        )
                        ->modalSubmitActionLabel('Sí, eliminar permanentemente')
                        ->successNotificationTitle('Productos eliminados permanentemente'),
                ]),
            ])
            ->emptyStateHeading('No hay productos registrados')
            ->emptyStateDescription('Cree su primer producto para comenzar.')
            ->emptyStateIcon('heroicon-o-cube')
            ->striped()
            ->persistFiltersInSession()
            ->persistSearchInSession();
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
            'index' => ListProducts::route('/'),
        ];
    }
}
