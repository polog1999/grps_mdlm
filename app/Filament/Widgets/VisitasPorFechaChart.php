<?php

namespace App\Filament\Widgets;

use App\Models\VisitaHistorico;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportar()
    {
        // 1. Replicamos la lógica de fechas del gráfico
        $fechaInicio = $this->desde ? Carbon::parse($this->desde) : now()->subDays(60);
        $fechaFin = $this->hasta ? Carbon::parse($this->hasta) : now();
        $diferenciaDias = $fechaInicio->diffInDays($fechaFin);

        $query = VisitaHistorico::query()
            ->where('origen', 'SISTEMA')
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id));

        if ($diferenciaDias > 90) {
            // Agrupación Mensual
            $results = $query->select(
                DB::raw("TO_CHAR(fecha, 'YYYY-MM') as periodo"),
                DB::raw('count(*) as total')
            )
                ->groupBy(DB::raw("TO_CHAR(fecha, 'YYYY-MM')"))
                ->orderBy(DB::raw("TO_CHAR(fecha, 'YYYY-MM')"), 'asc')
                ->get();

            $headerPeriodo = 'Mes (Año-Mes)';
        } else {
            // Agrupación Diaria
            $results = $query->select(
                DB::raw("fecha::date as periodo"),
                DB::raw('count(*) as total')
            )
                ->groupBy(DB::raw("fecha::date"))
                ->orderBy(DB::raw("fecha::date"), 'asc')
                ->get();

            $headerPeriodo = 'Fecha';
        }

        $coleccion = $results->map(fn($item) => [
            'periodo' => $item->periodo,
            'total' => $item->total,
        ]);

        return Excel::download(new class($coleccion, $headerPeriodo) implements FromCollection, WithHeadings, ShouldAutoSize {
            private $data;
            private $header;
            public function __construct($data, $header)
            {
                $this->data = $data;
                $this->header = $header;
            }
            public function collection()
            {
                return $this->data;
            }
            public function headings(): array
            {
                return [$this->header, 'Total de Visitas'];
            }
        }, 'visitas_por_periodo_' . now()->format('Ymd_His') . '.xlsx');
    }


    protected function getData(): array
    {
        // Si no hay filtro, limitamos por defecto a los últimos 60 días para evitar el error de histórico masivo
        $fechaInicio = $this->desde ? Carbon::parse($this->desde) : now()->subDays(60);
        $fechaFin = $this->hasta ? Carbon::parse($this->hasta) : now();

        $diferenciaDias = $fechaInicio->diffInDays($fechaFin);

        $query = VisitaHistorico::query()
            ->where('origen', 'SISTEMA')
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
