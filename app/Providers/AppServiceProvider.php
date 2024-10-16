<?php

namespace App\Providers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Livewire\Notifications;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TextInput::configureUsing(function (TextInput $textInput) {
            $textInput->inlineLabel();
        });
        Select::configureUsing(function (Select $select) {
            $select->inlineLabel();
        });
        Textarea::configureUsing(function (Textarea $textarea) {
            $textarea->inlineLabel();
        });

        Table::configureUsing(function (Table $table) {
            $table
                ->paginationPageOptions([10, 25, 50, 100])
                ->striped()
                ->deferLoading()
                ->recordClasses(fn (Model $record) => $record->deleted_at ? 'bg-red-100 text-gray-500 opacity-50' : '');

        });

        Notifications::alignment(Alignment::Center);

    }
}
