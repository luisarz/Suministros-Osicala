<?php

namespace App\Providers;

use App\Models\Employee;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

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

        Filament::serving(function () {
            $user = Auth::user();
            if ($user && $user->employee) {
                $employee = Employee::with('wherehouse')->find($user->employee->id);
                $sucursal = $employee->wherehouse;
                if ($sucursal) {
                    session(['branch_id' => $sucursal->id]);
                    session(['branch_name' => $sucursal->name]);
                    session(['branch_logo' => $sucursal->logo]); // Guardar el logo en la sesión

                } else {
                    session(['branch_id' => null]);
                    session(['branch_name' => null]);
                    session(['branch_logo' => null]);
                }
            }
//            dd(session('branch_logo'));

        });

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
                ->recordClasses(fn(Model $record) => $record->deleted_at ? 'bg-red-100 text-gray-500 opacity-50' : '');
        });

        Notifications::alignment(Alignment::Center);
//        Validaciones
        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };
// Check session data route
        Route::get('/check-session', function () {
            return [
                'branch_id' => session('branch_id'),
                'branch_name' => session('branch_name'),
                'branch_logo' => session('branch_logo'),
            ];
        });

    }
}
