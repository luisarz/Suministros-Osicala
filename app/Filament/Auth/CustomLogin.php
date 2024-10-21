<?php

namespace App\Filament\Auth;

use App\Models\Employee;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class CustomLogin extends Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getLoginFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])->statePath('data')
            )
        ];
    }

    protected function getLoginFormComponent(): Component

    {
        return TextInput::make('login')
            ->label(__('Usuario / Correo'))
            ->inlineLabel(false)
            ->required()
            ->autofocus()
            ->extraAttributes(['tabindex' => '1']);

    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Contraseña')
            ->password()
            ->inlineLabel(false)
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $login_type = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
//        dd($login_type);
        return [
            $login_type => $data['login'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (!Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();


        if (($user instanceof FilamentUser) && (!$user->canAccessPanel(Filament::getCurrentPanel()))) {
            Filament::auth()->logout();
            $this->throwFailureValidationException();
        }
        session()->regenerate();
//        if ($user && $user->employee) {
//            $employee = Employee::with('wherehouse')->find($user->employee->id);
//            $sucursal = $employee->wherehouse;
//            if ($sucursal) {
//                Session::put(['branch_id' => $sucursal->id]);
//                Session::put(['branch_name' => $sucursal->name]);
//                Session::put(['branch_logo' => $sucursal->logo]); // Guardar el logo en la sesión
//
//            }
//        }
//        dd(Session::all());  // Verifica si los datos de la sesión están presentes

//        eturn redirect()->intended(Filament::getUrl());
//        return $this->redirect('/admin');
        return app(LoginResponse::class);
    }


}
