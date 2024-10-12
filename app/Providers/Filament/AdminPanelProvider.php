<?php

namespace App\Providers\Filament;

use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;

class AdminPanelProvider extends PanelProvider
{
    /**
     * @throws \Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')

            ->path('admin')
//            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])

            ->brandLogo('<img src="https://filamentapp.com/img/logo.svg" alt="Filament" />')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->spa()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                GlobalSearchModalPlugin::make()

//                SpotlightPlugin::make(),
            ])
//            ->collapsedSidebarWidth('64px')
                ->collapsibleNavigationGroups()

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Almacén')
                    ->icon('heroicon-o-building-office')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Inventario')
                    ->icon('heroicon-o-building-office')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Facturación')
                    ->icon('heroicon-o-building-office')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Contabilidad')
                    ->icon('heroicon-o-building-office')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Recursos Humanos')
                    ->icon('heroicon-o-pencil')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Configuración')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Catálogos')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Seguridad')
                    ->icon('heroicon-o-shield-check')
                    ->collapsed(),
            ]);
    }
}
