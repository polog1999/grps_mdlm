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
            Stat::make('Última sesión', function () {
                $user = auth()->user();
            
            // Priorizamos last_login_at, si no existe, usamos current_login_at
            $displayDate = $user->last_login_at ?? $user->current_login_at;

            if (!$displayDate) {
                return 'N/A';
            }

            // Aseguramos que sea un objeto Carbon
            $date = Carbon::parse($displayDate);

            return $date->format('d/m/Y H:i') . ' (' . $date->diffForHumans() . ')';
        })
            ->description(fn() => auth()->user()->last_login_at 
                ? 'Fecha de tu acceso anterior' 
                : 'Esta es tu primera sesión')
            ->descriptionIcon('heroicon-m-calendar')
            ->color('success'),
        ];
    }
}
