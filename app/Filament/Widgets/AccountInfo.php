<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountInfo extends StatsOverviewWidget
{
    protected function getStats(): array
{
    return [
    Stat::make('Última sesión', function() {
        $lastLogin = auth()->user()->last_login_at;

        if (!$lastLogin) return 'N/A';

        $date = Carbon::parse($lastLogin);

        // Retorna: "08/05/2024 14:30 (hace 5 minutos)"
        return $date->format('d/m/Y H:i') . ' (' . $date->diffForHumans() . ')';
    })
    ->description('Tu último acceso registrado')
    ->descriptionIcon('heroicon-m-calendar')
    ->color('success'),
];
}
}
