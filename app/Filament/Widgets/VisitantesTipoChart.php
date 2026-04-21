<?php

namespace App\Filament\Widgets;

use App\Models\Visita;
use App\Models\VisitaHistorico;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class VisitantesTipoChart extends ChartWidget
{
    protected ?string $pollingInterval = '5s'; // Los gráficos pueden ser más lentos
    protected ?string $heading = 'Frecuencia de Visitantes';
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
        // 1. Definimos la base de los visitantes que queremos analizar (según el filtro)
        $visitantesDelPeriodo = VisitaHistorico::query()
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->select('numero_documento')
            ->distinct()
            ->pluck('numero_documento')
            ->toArray();

        if (empty($visitantesDelPeriodo)) {
            return [
                'datasets' => [['data' => [0, 0], 'backgroundColor' => ['#10b981', '#f59e0b']]],
                'labels' => ['Primera vez', 'Recurrentes'],
            ];
        }

        // 2. Contamos cuántos de ESOS visitantes han venido más de una vez en TODA la historia
        $recurrentes = VisitaHistorico::query()
            ->whereIn('numero_documento', $visitantesDelPeriodo)
           ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->select('numero_documento')
            ->groupBy('numero_documento')
            ->having(DB::raw('count(*)'), '>', 1)
            ->get()
            ->count();

        $totalUnicos = count($visitantesDelPeriodo);
        $soloUnaVez = $totalUnicos - $recurrentes;

        return [
            'datasets' => [
                [
                    'data' => [$soloUnaVez, $recurrentes],
                    'backgroundColor' => ['#10b981', '#f59e0b'],
                ],
            ],
            'labels' => ['Primera vez', 'Recurrentes'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Administrador OTIE', 'Control Interno - Supervisor']);
    }
}
