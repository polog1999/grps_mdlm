<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Facades\Filament;

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
        // Parche global para LibreOffice en Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $tempProfileDir = storage_path('app/temp/libreoffice_profile');
            if (!is_dir($tempProfileDir)) {
                @mkdir($tempProfileDir, 0755, true);
            }
            putenv("HOME={$tempProfileDir}");
            $_SERVER['HOME'] = $tempProfileDir;
        }
    }
}
