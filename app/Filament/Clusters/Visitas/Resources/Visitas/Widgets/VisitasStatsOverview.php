<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Widgets;

use App\Filament\Clusters\Visitas\Resources\Visitas\Pages\ListVisitas;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Visita; // Asegúrate de tener tu modelo
use App\Models\VisitaHistorico;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class VisitasStatsOverview extends BaseWidget
{
    use InteractsWithPageTable; // Esto permite leer los filtros de la página
    protected function getTablePage(): string
    {
        return ListVisitas::class;
    }
    protected function getStats(): array
    {
        // Esta es la query que ya trae los filtros de la tabla aplicados
        $queryBase = $this->getPageTableQuery();
    return [
    // 1. Total de Visitas: Azul (Confianza/General)
    Stat::make('Visitas Seleccionadas', (clone $queryBase)->count())
        ->description('Según los filtros actuales')
        ->descriptionIcon('heroicon-m-user-group')
        ->chart([10, 15, 8, 12, 20, 14, 25]) // Gráfico de tendencia
        ->color('primary'), // Azul

    // 2. En Sede: Esmeralda/Verde (Activo/Seguro)
    Stat::make('En Sede (Hoy)', (clone $queryBase)->whereDate('fecha', today())->whereNull('hora_salida')->count())
        ->description('Personas que aún no salen')
        ->descriptionIcon('heroicon-m-map-pin')
        ->extraAttributes([
            'class' => 'cursor-pointer', // Opcional: si quieres que parezca clickeable
        ])
        ->color('success'), // Verde


    // 4. EXTRA: Si quieres un toque de "Alerta" o Info especial
    // Puedes agregar un cuarto stat para ingresos de hoy en un color distinto
    Stat::make('Ingresos (Hoy)', (clone $queryBase)->whereDate('fecha', today())->count())
        ->description('Nuevos ingresos de hoy')
        ->descriptionIcon('heroicon-m-bolt')
        ->color('info'), // Celeste/Cian

          // 3. Salidas: Ámbar/Naranja (Precaución/Finalizado)
    Stat::make('Salidas Registradas (Hoy)', (clone $queryBase)->whereDate('fecha', today())->whereNotNull('hora_salida')->count())
        ->description('Personas que ya se retiraron')
        ->descriptionIcon('heroicon-m-arrow-left-on-rectangle')
        ->color('warning'), // Ámbar/Naranja
];
    }
}
