<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Helpers\KardexHelper;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleItem;
use EightyNine\FilamentPageAlerts\PageAlert;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;
    public string $codigoCancelacion;

    public function getTitle(): string
    {
        return '';
    }
    public function mount(...$params): void
    {
        parent::mount(...$params);
        $this->codigoCancelacion = Str::upper(Str::random(4));
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Volver'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Enviar Orden')
                ->color('success')
                ->icon('heroicon-o-check')
                ->action('save')
                ->extraAttributes([
                    'class' => 'alig', // Tailwind para ajustar el margen alinearlo a la derecha

                ]),

            Action::make('cancelSale')
                ->label('Elimianr Orden')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Confirmación')
                ->modalSubheading("Para cancelar esta venta, escribe el siguiente código:")
                ->modalButton('Sí, cancelar venta')

                ->form([
                    Placeholder::make('codigo_mostrado')
                        ->label('Código:')
                        ->inlineLabel(true)
                        ->content("{$this->codigoCancelacion}")
                        ->extraAttributes(['style' => 'font-weight: bold; color: #dc2626']), // rojo y negrita

                    TextInput::make('confirmacion')
                        ->label('Codigo')
                        ->required()
                        ->inlineLabel(true)
                        ->rules(["in:{$this->codigoCancelacion}"])
                        ->validationMessages([
                            'in' => 'El código ingresado no coincide.',
                        ]),
                ])

                ->action(function (Actions\DeleteAction $delete)  {
                    if ($this->record->is_dte) {
                        PageAlert::make()
                            ->title('Error al anular venta')
                            ->body('No se puede cancelar una venta con DTE.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Eliminar la venta y los elementos relacionados
                    SaleItem::where('sale_id', $this->record->id)->delete();
                    $this->record->delete();

                    Notification::make()
                        ->title('Venta cancelada')
                        ->body('La venta y sus elementos relacionados han sido eliminados con éxito.')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('index'));
                }),
            Action::make('Regresar')
                ->color('primary')
                ->label('Volver')
                ->icon('heroicon-o-arrow-uturn-left')
                ->action(function (Actions\DeleteAction $delete)  {

                    $this->redirect(static::getResource()::getUrl('index'));
                }),
        ];
    }

    #[On('refreshSale')]
    public function refresh(): void
    {
    }

    protected function afterSave(): void
    {
//        Notification::make('Orden enviada')
//            ->title('Orden enviada')
//            ->body('La orden ha sido enviada correctamente')
//            ->success()
//            ->send();

        PageAlert::make()
            ->title('Orden enviada')
            ->body('La orden ha sido enviada correctamente')
            ->success()
            ->send();
        $this->redirect(static::getResource()::getUrl('index'));

    }


}
