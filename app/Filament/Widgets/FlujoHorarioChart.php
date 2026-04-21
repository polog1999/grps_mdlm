<?php

namespace App\Filament\Widgets;

use App\Models\VisitaHistorico;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class FlujoHorarioChart extends ChartWidget
{
    // 1. Debe ser static para que funcione el autorefresco
    protected  ?string $pollingInterval = '5s';
    protected ?string $heading = 'Flujo de Visitas por Hora (Ingresos vs Salidas)';

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

        // 2. IMPORTANTE: Sin esto el gráfico no se redibuja al filtrar
        $this->dispatch('$refresh');
    }

    protected function getData(): array
    {
        // Consulta de Ingresos
        $ingresosRaw = VisitaHistorico::query()
            ->select(DB::raw("EXTRACT(HOUR FROM fecha) as hora"), DB::raw("count(*) as total"))
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
            ->groupBy('hora')
            ->get()
            ->pluck('total', 'hora')
            ->toArray();

        // Consulta de Salidas
        $salidasRaw = VisitaHistorico::query()
            ->select(DB::raw("EXTRACT(HOUR FROM hora_salida) as hora"), DB::raw("count(*) as total"))
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
            ->whereNotNull('hora_salida')
            ->groupBy('hora')
            ->get()
            ->pluck('total', 'hora')
            ->toArray();

        // 3. Sincronización: Creamos un array de horas (ejemplo: de 07:00 a 19:00)
        // Puedes usar range(0, 23) si quieres el día completo
        $labels = [];
        $dataIngresos = [];
        $dataSalidas = [];

        foreach (range(7, 18) as $hora) {
            $labels[] = "{$hora}:00";
            $dataIngresos[] = $ingresosRaw[$hora] ?? 0;
            $dataSalidas[] = $salidasRaw[$hora] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $dataIngresos,
                    'fill' => 'start',
                    'borderColor' => '#10b981', // Verde
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Salidas',
                    'data' => $dataSalidas,
                    'fill' => 'start',
                    'borderColor' => '#ef4444', // Rojo
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Administrador OTIE', 'Control Interno - Supervisor']);
    }
}
