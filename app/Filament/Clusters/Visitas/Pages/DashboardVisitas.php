<?php

namespace App\Filament\Clusters\Visitas\Pages; // 1. CORREGIDO: El namespace debe ser de Pages Asegúrate de que sea este namespace


use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Filament\Widgets\FlujoHorarioChart;
use App\Filament\Widgets\UltimasVisitasTable;
use App\Filament\Widgets\VisitantesTipoChart;
use App\Filament\Widgets\VisitasEstadoChart;
use App\Filament\Widgets\VisitasPorAreaChart as WidgetsVisitasPorAreaChart;
use App\Filament\Widgets\VisitasStatsOverview;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

use BackedEnum; // IMPORTANTE: Falta esta importación para el tipado
class DashboardVisitas extends Page
{

    protected static ?string $cluster = VisitasCluster::class;

    // SOLUCIÓN AL ERROR: Usamos la unión de tipos exacta que pide la clase Page

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $navigationLabel = 'Tablero de Control';

    protected static ?int $navigationSort = 1;

    // Añade esto debajo del $navigationSort
    protected static bool $shouldRegisterNavigation = true;

    // Recuerda: en Filament 3, $view NO es static
    protected string $view = 'filament.clusters.visitas.pages.dashboard-visitas';
    protected static ?string $title = 'Análisis de Visitas';

    // Propiedades para los filtros
    public ?string $desde = null;
    public ?string $hasta = null;
    public ?string $area_id = null;


    protected function getHeaderActions(): array
    {
        return [
            Action::make('filtrar')
                ->label('Filtros de Búsqueda')
                ->icon('heroicon-m-funnel')
                ->fillForm([
                    'desde' => $this->desde,
                    'hasta' => $this->hasta,
                    'area_id' => $this->area_id,
              
                ])
                ->form([
                    DatePicker::make('desde'),
                    DatePicker::make('hasta'),
                    Select::make('area_id')
                        ->label('Filtrar por Área')
                        ->options(\App\Models\Area::all()->pluck('nombre', 'id_unidad_organica'))
                        ->searchable()
                        ->live() // Añade esto para que Livewire rastree mejor el cambio
                        ->placeholder('Seleccione un área'),
   
                ])
                ->action(function (array $data) {
                    $this->desde = $data['desde'];
                    $this->hasta = $data['hasta'];
                    $this->area_id = $data['area_id'];

                    // Esto refresca los widgets automáticamente
                    // Dentro del action() en DashboardVisitas.php
                  $this->dispatch('updateDashboardCharts', [
    'desde' => $this->desde,
    'hasta' => $this->hasta,
    'area' => $this->area_id, // Cambiado a 'area' para que coincida con el widget
   
]);
                })
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VisitasStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            WidgetsVisitasPorAreaChart::class,
            FlujoHorarioChart::class,
            VisitasEstadoChart::class,
            VisitantesTipoChart::class,
            UltimasVisitasTable::class
        ];
    }
    public function getHeaderWidgetsColumns(): int | array
    {
        return 4; // Las 4 tarjetitas de Stats arriba en una sola fila
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 2; // Los 2 gráficos abajo, uno al lado del otro
    }
}
