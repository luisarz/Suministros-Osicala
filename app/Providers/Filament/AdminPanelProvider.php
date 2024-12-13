<?php

namespace App\Providers\Filament;

use App\Filament\Auth\CustomLogin;
use App\Filament\Resources\LogResource;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Pages\Auth\EditProfile;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Rmsramos\Activitylog\ActivitylogPlugin;

class AdminPanelProvider extends PanelProvider
{
//    public function render()
//    {
//        $branchId = session('branch_id');
//        $branchName = session('branch_name');
//        $branchLogo = session('branch_logo');
//
//        return view('filament.pages.dashboard', [
//            'branchId' => $branchId,
//            'branchName' => $branchName,
//            'branchLogo' => $branchLogo,
//        ]);
//    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function panel(Panel $panel): Panel
    {

        return $panel
            ->brandLogo(fn() => view('logo'))
            ->brandLogoHeight('5rem')
            ->default()
            ->font('serif')
            ->sidebarWidth('20rem')
            ->id('admin')
            ->path('admin')
            ->profile(isSimple: false)
            ->authGuard('web')
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->login(CustomLogin::class)
            ->maxContentWidth('full')
            ->spa()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Hasnayeen\Themes\ThemesPlugin::make(),
                GlobalSearchModalPlugin::make(),
                ActivitylogPlugin::make()->resource(LogResource::class),

            ])
            ->collapsibleNavigationGroups()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Almacén')
                    ->icon('heroicon-o-building-office')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Inventario')
                    ->icon('heroicon-o-circle-stack')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Facturación')
                    ->icon('heroicon-o-shopping-cart')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Caja Chica')
                    ->icon('heroicon-o-currency-dollar')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Contabilidad')
                    ->icon('heroicon-o-building-office')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Recursos Humanos')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Configuración')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Catálogos Hacienda')
                    ->icon('heroicon-o-magnifying-glass-circle')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Seguridad')
                    ->icon('heroicon-o-shield-check')
                    ->collapsed(),
            ]);

    }
}