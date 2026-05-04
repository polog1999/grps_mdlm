<?php

namespace App\Filament\Widgets;

use App\Models\Visita;
use App\Models\VisitaHistorico;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class VisitantesTipoChart extends ChartWidget
{
    protected ?string $pollingInterval = '5s'; // Los gráficos pueden ser más lentos
    protected ?string $heading = 'Frecuencia de Visitantes';
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
 /**
     * Título con botón de exportar pequeño y moderno
     */
    public function getHeading(): string | Htmlable | null
    {
        return new HtmlString('
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 10px;">
                <span style="font-size: 0.95rem; font-weight: 600; color: #374151;">
                    Frecuencia de Visitantes
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
                    <svg wire:loading.remove style="width: 14px; height: 14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>

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
        // 1. Obtenemos los visitantes únicos del periodo
        $visitantesDelPeriodo = VisitaHistorico::query()
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
            ->select('numero_documento')
            ->distinct()
            ->pluck('numero_documento')
            ->toArray();

        if (empty($visitantesDelPeriodo)) {
            $soloUnaVez = 0;
            $recurrentes = 0;
        } else {
            // 2. Contamos recurrentes
            $recurrentes = VisitaHistorico::query()
                ->whereIn('numero_documento', $visitantesDelPeriodo)
                ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
                ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
                ->select('numero_documento')
                ->groupBy('numero_documento')
                ->having(DB::raw('count(*)'), '>', 1)
                ->get()
                ->count();

            $soloUnaVez = count($visitantesDelPeriodo) - $recurrentes;
        }

        // 3. Preparamos data para Excel
        $coleccion = collect([
            ['Tipo' => 'Visitantes por Primera Vez', 'Cantidad' => $soloUnaVez],
            ['Tipo' => 'Visitantes Recurrentes', 'Cantidad' => $recurrentes],
            ['Tipo' => 'TOTAL VISITANTES ÚNICOS', 'Cantidad' => ($soloUnaVez + $recurrentes)],
        ]);

        return Excel::download(new class($coleccion) implements FromCollection, WithHeadings, ShouldAutoSize {
            private $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return $this->data; }
            public function headings(): array {
                return ['Categoría de Visitante', 'Cantidad de Personas'];
            }
        }, 'frecuencia_visitantes_' . now()->format('Ymd_His') . '.xlsx');
    }

    protected function getData(): array
    {
        // 1. Definimos la base de los visitantes que queremos analizar (según el filtro)
        $visitantesDelPeriodo = VisitaHistorico::query()
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
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
           ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
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
