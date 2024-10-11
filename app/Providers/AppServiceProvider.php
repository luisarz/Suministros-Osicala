<?php

namespace App\Providers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\ServiceProvider;

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
    }
}
