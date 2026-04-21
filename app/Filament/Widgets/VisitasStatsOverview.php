<?php

namespace App\Filament\Widgets;


use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class VisitasStatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '3s'; // Se actualiza cada 10 segundos
    // 1. Definimos las propiedades que recibirán el filtro
    public ?string $desde = null;
    public ?string $hasta = null;
    public ?string $area_id = null;

    // 2. Este es el "oído" que escucha a la página
    #[On('updateDashboardCharts')]
    public function updateFilters(array $data): void
    {
        $this->desde = $data['desde'];
        $this->hasta = $data['hasta'];
        $this->area_id = $data['area'] ?? null;

        // Esto fuerza al widget a volver a ejecutar la consulta SQL
    }
    protected function getStats(): array
    {
        // 3. Usa las propiedades en tu consulta SQL
        $query = \App\Models\VisitaHistorico::query();

        if ($this->desde) {
            $query->whereDate('fecha', '>=', $this->desde);
        }
        if ($this->hasta) {
            $query->whereDate('fecha', '<=', $this->hasta);
        }
        if ($this->area_id) {
            $query->where('area_id', $this->area_id);
        }
        // Calcula el promedio de minutos entre ingreso y salida
        // En PostgreSQL restamos las fechas y extraemos el total de segundos para convertir a minutos
        $promedioMinutos = (clone $query)
            ->whereNotNull('hora_salida')
            ->select(DB::raw("AVG(EXTRACT(EPOCH FROM (hora_salida - hora_ingreso)) / 60) as promedio"))
            ->first()->promedio ?? 0;

        return [
            // 2. IMPORTANTE: Usamos clone() para no ensuciar la consulta principal en cada tarjeta
            Stat::make('Total Visitas', (clone $query)->count())
                ->description('Según filtros aplicados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Visitantes Hoy', (clone $query)->whereDate('fecha', today())->count())
                ->description('Ingresos del día filtrado')
                ->color('info'),

            Stat::make('En Sede', (clone $query)->whereNull('hora_salida')->count())
                ->description('Personas aún dentro')
                ->color('warning'),
            Stat::make('Permanencia Promedio', round($promedioMinutos) . ' min')
                ->description('Tiempo de estadía por visita')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),

        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->hasRole('Administrador OTIE');
    }
}
