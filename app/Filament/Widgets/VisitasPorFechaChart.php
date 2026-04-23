<?php

namespace App\Filament\Widgets;

use App\Models\VisitaHistorico;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class VisitasPorFechaChart extends ChartWidget
{
    protected ?string $pollingInterval = '5s';
    protected ?string $heading = 'Cantidad de Visitas por Periodo';
    protected ?string $maxHeight = '300px';

    public ?string $desde = null;
    public ?string $hasta = null;
    public ?string $area_id = null;
    public ?string $sede_id = null;

    #[On('updateDashboardCharts')]
    public function updateFilters(array $data): void
    {
        $this->desde = $data['desde'] ?? null;
        $this->hasta = $data['hasta'] ?? null;
        $this->area_id = $data['area'] ?? null;
        $this->sede_id = $data['sede'] ?? null;

        $this->dispatch('$refresh');
    }

    protected function getData(): array
    {
        // Si no hay filtro, limitamos por defecto a los últimos 60 días para evitar el error de histórico masivo
        $fechaInicio = $this->desde ? Carbon::parse($this->desde) : now()->subDays(60);
        $fechaFin = $this->hasta ? Carbon::parse($this->hasta) : now();
        
        $diferenciaDias = $fechaInicio->diffInDays($fechaFin);

        $query = VisitaHistorico::query()
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id));

        // Si el rango es mayor a 90 días o estamos viendo el histórico completo
        if ($diferenciaDias > 90) {
            $results = $query->select(
                DB::raw("TO_CHAR(fecha, 'YYYY-MM') as periodo"), 
                DB::raw('count(*) as total')
            )
            ->groupBy(DB::raw("TO_CHAR(fecha, 'YYYY-MM')"))
            ->orderBy(DB::raw("TO_CHAR(fecha, 'YYYY-MM')"), 'asc')
            ->get() // Usamos get para asegurar la colección antes del pluck
            ->pluck('total', 'periodo')
            ->toArray();

            $labels = array_keys($results);
            $values = array_values($results);
        } else {
            // Rango corto: Agrupación por día exacta para Postgres
            $results = $query->select(
                DB::raw("fecha::date as periodo"),
                DB::raw('count(*) as total')
            )
            ->groupBy(DB::raw("fecha::date"))
            ->orderBy(DB::raw("fecha::date"), 'asc')
            ->get()
            ->pluck('total', 'periodo')
            ->toArray();

            $labels = [];
            $values = [];
            $period = CarbonPeriod::create($fechaInicio->format('Y-m-d'), $fechaFin->format('Y-m-d'));

            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $labels[] = $date->format('d/m');
                $values[] = $results[$formattedDate] ?? 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Visitas',
                    'data' => $values,
                    'backgroundColor' => '#6366f1',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Administrador OTIE', 'Control Interno - Supervisor']);
    }
}