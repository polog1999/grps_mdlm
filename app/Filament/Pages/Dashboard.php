<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function mount(): void
    {
        $user = auth()->user();

        if ($user && $user->hasAnyRole(['Control Interno - Registros','Control Interno - Supervisor'])) {
            // En lugar de esperar al error 403, lo mandamos nosotros
            redirect('/admin/visitas/visitas');
        }
    }
  public static function shouldRegisterNavigation(): bool
    {
        // Esto OCULTA el botón "Escritorio" del menú lateral
        // para ese rol, pero permite que la página cargue (y redirija)
        return !auth()->user()->hasAnyRole(['Control Interno - Registros','Control Interno - Supervisor']);
    }

}
