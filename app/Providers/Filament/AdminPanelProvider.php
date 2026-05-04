<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
// use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentColor;
use Filament\Enums\ThemeMode;
use App\Filament\Pages\Register;
use Filament\Support\Enums\Width;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Auth;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->favicon(asset('favicon.png'))
            ->defaultThemeMode(ThemeMode::Light)
            ->brandName('Intranet')
            //->brandLogo(asset('images/logo.svg'))
            ->authGuard('web')
            ->authPasswordBroker('users')
            ->login(Login::class)
            ->profile()
            ->topNavigation()
            ->passwordReset(RequestPasswordReset::class)
            ->emailVerification()
            ->globalSearch(false)
            ->sidebarFullyCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->plugin(\MarcoGermani87\FilamentCaptcha\FilamentCaptcha::make())

            ->colors([
                'primary' => Color::Amber,
                'white' => 'rgba(255, 255, 255, 1)',
                'danger-high' => 'rgba(231, 120, 120, 1)',
                'danger-very-high' => 'rgba(110, 6, 6, 1)',
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class, // Al poner esto aquí, Filament usa tu lógica de canAccess
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
           ->databaseNotifications() // <--- ESTO ES VITAL
        ->databaseNotificationsPolling('2s')

        ; // Actualiza cada 3 segundos para ver el % real

    }
}
