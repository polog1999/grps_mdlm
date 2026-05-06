<?php

namespace App\Filament\Widgets;

use App\Models\Visita;
use App\Models\VisitaHistorico;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions; // Importante
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On; // IMPORTANTE: Para que funcione el atributo #[On]
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class VisitasPorAreaChart extends ChartWidget implements HasActions
{
    use InteractsWithActions;
    protected ?string $pollingInterval = '5s'; // Los gráficos pueden ser más lentos
    // protected ?string $heading = 'Ranking de Áreas más Visitadas';
    protected ?string $maxHeight = '300px';

    // Propiedades para almacenar los filtros
    public ?string $desde = null;
    public ?string $hasta = null;
    public ?string $area_id = null;
    public ?string $sede_id = null;


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
        $this->sede_id = $data['sede'] ?? null;

        // ESTO ES VITAL: Avisa al componente de Chart.js que debe redibujarse
        // $this->dispatch('$refresh'); 
    }
    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return new \Illuminate\Support\HtmlString('
        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 10px;">
            <span style="font-size: 0.95rem; font-weight: 600; color: #374151;">
                Cantidad de Visitas por Periodo
            </span>

            <button 
                wire:click="exportar" 
                wire:loading.attr="disabled"
                type="button" 
                style="
                    display: inline-flex; 
                    align-items: center; 
                    gap: 6px; 
                    background-color: #10b981; 
                    color: white; 
                    padding: 5px 12px; 
                    border-radius: 6px; 
                    font-size: 12px; 
                    font-weight: 600; 
                    border: none; 
                    cursor: pointer; 
                    transition: background 0.2s;
                    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
                "
                onmouseover="this.style.backgroundColor=\'#059669\'"
                onmouseout="this.style.backgroundColor=\'#10b981\'"
            >
                <!-- Icono de Excel (Se oculta al cargar) -->
                <svg wire:loading.remove style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>

                <!-- Spinner Animado (Solo se muestra al cargar) -->
                <svg wire:loading class="animate-spin" style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <span wire:loading.remove>Excel</span>
                <span wire:loading>...</span>
            </button>
        </div>
    ');
    }
    protected function getData(): array
    {
        // Consulta para obtener el Top 10 de áreas con más visitas
        $data = VisitaHistorico::query()
            ->select('area_id', DB::raw('count(*) as total')) // Usa id_unidad_organica
            ->where('origen', 'SISTEMA')
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            // Si el usuario filtra por un área específica, el gráfico solo mostrará esa (o el ranking general si es null)
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
            ->groupBy('area_id')
            ->orderByDesc('total')
            ->limit(10)
            // ->with('area1')
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
            'labels' => $data->map(fn($item) => $item->area1->nombre ?? 'Sin Área')->toArray(),
        ];
    }
    public function exportar()
    {
        // 1. Obtenemos la data filtrada
        $data = VisitaHistorico::query()
            ->select('area_id', DB::raw('count(*) as total'))
            ->where('origen', 'SISTEMA')
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
            ->groupBy('area_id')
            ->orderByDesc('total')
            ->get();

        // 2. Transformamos la data para el Excel
        $coleccion = $data->map(fn($item) => [
            'area' => $item->area1->nombre ?? 'Sin Área',
            'total' => $item->total,
        ]);

        // 3. Generamos la descarga del Excel (.xlsx)
        return Excel::download(new class($coleccion) implements FromCollection, WithHeadings, ShouldAutoSize {
            private $data;
            public function __construct($data)
            {
                $this->data = $data;
            }
            public function collection()
            {
                return $this->data;
            }
            public function headings(): array
            {
                return ['Área Destino', 'Total de Visitas'];
            }
        }, 'ranking_areas_' . now()->format('Y-m-d_Hmi') . '.xlsx');
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
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Administrador OTIE', 'Control Interno - Supervisor']);
    }
}
