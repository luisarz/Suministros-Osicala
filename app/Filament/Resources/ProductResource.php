<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Category;
use App\Models\Marca;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\HtmlString;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $label = 'Producto';
    protected static ?string $pluralLabel = 'Productos';
    protected static string | \UnitEnum | null $navigationGroup = 'Almacén';
    protected static ?string $recordTitleAttribute = 'name';

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
                Section::make('Información General')
                    ->description('Datos básicos del producto')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del Producto')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Aceite para motor 20W-50')
                            ->helperText('Nombre completo y descriptivo del producto')
                            ->columnSpanFull(),

                        TextInput::make('aplications')
                            ->label('Aplicaciones')
                            ->placeholder('Motor; Transmisión; Hidráulico')
                            ->helperText('Separar con punto y coma (;)')
                            ->columnSpanFull(),

                        TextInput::make('sku')
                            ->label('SKU (Código Interno)')
                            ->maxLength(255)
                            ->placeholder('Ej: ACE-20W50-001')
                            ->helperText('Código único del producto en el sistema')
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),

                        TextInput::make('bar_code')
                            ->label('Código de Barras')
                            ->maxLength(255)
                            ->placeholder('Ej: 7501234567890')
                            ->helperText('Código de barras del fabricante (EAN/UPC)')
                            ->unique(ignoreRecord: true)
                            ->numeric(),

                    ])->columns(2),

                Section::make('Clasificación')
                    ->description('Categorización del producto')
                    ->icon('heroicon-o-tag')
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
                            ->helperText('Selecciona o crea una categoría'),

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
                            ->helperText('Selecciona o crea una marca'),

                        Select::make('unit_measurement_id')
                            ->label('Unidad de Medida')
                            ->preload()
                            ->searchable()
                            ->relationship('unitMeasurement', 'description')
                            ->required()
                            ->helperText('Unidad de presentación del producto'),

                    ])->columns(3),

                Section::make('Configuración del Producto')
                    ->description('Opciones de comportamiento del producto')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Toggle::make('is_service')
                            ->label('Es un Servicio')
                            ->helperText('Activar si es un servicio en lugar de producto físico')
                            ->inline(false),

                        Toggle::make('is_active')
                            ->label('Producto Activo')
                            ->default(true)
                            ->helperText('Desactivar para ocultar de ventas sin eliminar')
                            ->inline(false),

                        Toggle::make('is_grouped')
                            ->label('Producto Compuesto')
                            ->default(false)
                            ->helperText('Activar si el producto está compuesto por otros productos')
                            ->inline(false),

                        Toggle::make('is_taxed')
                            ->label('Producto Gravado')
                            ->default(true)
                            ->helperText('Aplica IVA en ventas y compras')
                            ->inline(false),
                    ])->columns(4),

                Section::make('Imagen del Producto')
                    ->description('Fotografía o imagen representativa del producto')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('images')
                            ->label('')
                            ->directory('products')
                            ->image()
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
                            ->helperText('📸 Tamaño máximo: 2MB | Formatos: JPG, PNG, WEBP | Recomendado: 800x800px')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Imagen')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        // Genera un avatar bonito con las iniciales del producto
                        $nombre = urlencode($record->name);
                        $iniciales = urlencode(substr($record->name, 0, 2));
                        // Colores aleatorios pero consistentes basados en el nombre
                        $colors = ['3b82f6', '8b5cf6', 'ec4899', 'f97316', '10b981', '06b6d4', 'f59e0b', 'ef4444'];
                        $colorIndex = abs(crc32($record->name)) % count($colors);
                        $bgColor = $colors[$colorIndex];

                        return "https://ui-avatars.com/api/?name={$iniciales}&color=ffffff&background={$bgColor}&bold=true&size=128";
                    })
                    ->size(40),

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('Producto')
                    ->weight(FontWeight::SemiBold)
                    ->sortable()
                    ->icon('heroicon-m-cube')
                    ->wrap()
                    ->searchable()
                    ->description(fn (Product $record): string => $record->sku ? "SKU: {$record->sku}" : ''),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->icon('heroicon-m-tag')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->icon('heroicon-m-check-badge')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('unitMeasurement.description')
                    ->label('U. Medida')
                    ->icon('heroicon-m-scale')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('aplications')
                    ->label('Aplicaciones')
                    ->badge()
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->separator(';')
                    ->limit(2)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->copyable()
                    ->icon('heroicon-m-qr-code')
                    ->copyMessage('SKU copiado')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('bar_code')
                    ->icon('heroicon-m-bars-3-bottom-left')
                    ->label('Código Barras')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->searchable(),

            ])
            ->paginationPageOptions([
                10, 25, 50, 100
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->relationship('category', 'name')
                    ->options(fn() => Category::whereNotNull('parent_id')->pluck('name', 'id')->toArray()),

                SelectFilter::make('marca_id')
                    ->label('Marca')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->relationship('marca', 'nombre')
                    ->options(fn() => Marca::pluck('nombre', 'id')->toArray()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_service')
                    ->label('Tipo')
                    ->placeholder('Todos')
                    ->trueLabel('Servicios')
                    ->falseLabel('Productos')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_taxed')
                    ->label('Gravado')
                    ->placeholder('Todos')
                    ->trueLabel('Con IVA')
                    ->falseLabel('Sin IVA')
                    ->native(false),

                TrashedFilter::make()
                    ->label('Eliminados')
                    ->native(false),
            ])
            ->filtersFormColumns(3)
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Editar producto'),

                ReplicateAction::make()
                    ->icon('heroicon-o-document-duplicate')
                    ->color('success')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Duplicar producto')
                    ->excludeAttributes(['sku', 'bar_code']),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Eliminar producto'),

                RestoreAction::make()
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->label('')
                    ->iconSize(IconSize::Large)
                    ->tooltip('Restaurar producto'),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Eliminar seleccionados'),
                    RestoreBulkAction::make()
                        ->label('Restaurar seleccionados'),
                ])
            ])
            ->emptyStateHeading('No hay productos registrados')
            ->emptyStateDescription('Comienza agregando tu primer producto al sistema.')
            ->emptyStateIcon('heroicon-o-cube')
            ->striped()
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession();
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
