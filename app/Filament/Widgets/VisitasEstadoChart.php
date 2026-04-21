<?php

namespace App\Filament\Widgets;

use App\Models\Visita;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class VisitasEstadoChart extends ChartWidget
{
    protected ?string $pollingInterval = '5s'; // Los gráficos pueden ser más lentos
    protected ?string $heading = 'Estado de Visitas (Ingresos vs Salidas)';
    public ?string $desde = null;
    public ?string $hasta = null;
    public ?string $area_id = null;

    #[On('updateDashboardCharts')]
    public function updateFilters(array $data): void
    {
        $this->desde = $data['desde'] ?? null;
        $this->hasta = $data['hasta'] ?? null;
        $this->area_id = $data['area'] ?? null;
        $this->dispatch('$refresh');
    }

    protected function getData(): array
    {
        $query = Visita::query()
            ->when($this->desde, fn($q) => $q->whereDate('created_at', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('created_at', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id));

        $ingresos = (clone $query)->count();
        $salidas = (clone $query)->whereNotNull('fecha_salida')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Ingresos',
                    'data' => [$ingresos],
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Salidas Registradas',
                    'data' => [$salidas],
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => ['Resumen de Flujo'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Administrador OTIE','Control Interno - Supervisor']);
    }
}
