<?php

namespace App\Filament\Widgets;

use App\Models\Visita;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On; // IMPORTANTE: Para que funcione el atributo #[On]

class VisitasPorAreaChart extends ChartWidget
{
    protected ?string $pollingInterval = '5s'; // Los gráficos pueden ser más lentos
    protected ?string $heading = 'Ranking de Áreas más Visitadas';
    protected ?string $maxHeight = '300px';

    // Propiedades para almacenar los filtros
    public ?string $desde = null;
    public ?string $hasta = null;
    public ?string $area_id = null;

    /**
     * Escuchamos el evento. 
     * En Livewire 3 se usa el atributo #[On]
     */
    #[On('updateDashboardCharts')] 
    public function updateFilters(array $data): void
    {
        $this->desde = $data['desde'] ?? null;
        $this->hasta = $data['hasta'] ?? null;
        $this->area_id = $data['area'] ?? null;

        // ESTO ES VITAL: Avisa al componente de Chart.js que debe redibujarse
        // $this->dispatch('$refresh'); 
    }

    protected function getData(): array
    {
        // Consulta para obtener el Top 10 de áreas con más visitas
        $data = Visita::query()
            ->select('area_id', DB::raw('count(*) as total')) // Usa id_unidad_organica
            ->when($this->desde, fn($q) => $q->whereDate('created_at', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('created_at', '<=', $this->hasta))
            // Si el usuario filtra por un área específica, el gráfico solo mostrará esa (o el ranking general si es null)
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->groupBy('area_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('area') 
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de Visitas',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $data->map(fn($item) => $item->area->nombre ?? 'Sin Área')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }
}