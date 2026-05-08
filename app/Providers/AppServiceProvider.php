<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Facades\Filament;

use App\Models\CertificadoLicenciaFuncionamiento;
use App\Observers\CertificadoLicenciaFuncionamientoObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

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
        Event::listen(Login::class, function ($event) {
            $user = $event->user;

            // 1. Pasamos la que era "actual" a "última"
            $user->last_login_at = $user->current_login_at;

            // 2. Registramos la entrada de este preciso momento
            $user->current_login_at = now();

            $user->save();
        });
    }
}
