<?php

namespace App\Filament\Clusters\Visitas\Pages; // 1. CORREGIDO: El namespace debe ser de Pages Asegúrate de que sea este namespace


use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Filament\Exports\VisitaDashboardExporter;
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
use Filament\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DashboardVisitas extends Page
{

    protected static ?string $cluster = VisitasCluster::class;


    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $navigationLabel = 'Tablero de Control';

    protected static ?int $navigationSort = 2;

    // Añade esto debajo del $navigationSort
    protected static bool $shouldRegisterNavigation = true;

    // Recuerda: en Filament 3, $view NO es static
    protected string $view = 'filament.clusters.visitas.pages.dashboard-visitas';
    protected static ?string $title = 'Análisis de Visitas';

    // Propiedades para los filtros
    public ?string $desde = null;
    public ?string $hasta = null;
    public ?string $area_id = null;
    public ?string $sede_id = null;

    // public function mount(): void
    // {
    //     $user = auth()->user();

    //     // Si el usuario es Supervisor y tiene una sede asignada (ajusta el nombre del campo sede_id si es diferente)
    //     if ($user->hasRole('Control Interno - Supervisor') && $user->sede_id) {
    //         $this->sede_id = (string) $user->sede_id;
    //     }
    // }
    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasAnyRole(['Administrador OTIE', 'Control Interno - Supervisor']);
    }

    /**
     * NAVEGACIÓN: Muestra el botón en el Cluster pero lo oculta en el escritorio.
     */
    public static function shouldRegisterNavigation(): bool
    {
        // 1. Si no tiene el rol, no se muestra nunca
        if (!auth()->user()->hasAnyRole(['Administrador OTIE', 'Control Interno - Supervisor'])) {
            return false;
        }

        // 2. Tu lógica de URL que ya te funcionó:
        $url = request()->url();

        // Si la URL es la raíz del admin, lo ocultamos para que no ensucie el escritorio
        if (preg_match('/\/admin\/?$/', $url)) {
            return false;
        }

        // En cualquier otro caso (cuando ya estás en /visitas), se muestra el botón
        return true;
    }
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
                    'sede_id' => $this->sede_id,

                ])
                ->form([
                    DatePicker::make('desde'),
                    DatePicker::make('hasta'),
                    Select::make('area_id')
                        ->label('Filtrar por Área')
                        ->options(\App\Models\Area::query()->when($this->sede_id, fn($q) => $q->where('id_sede', $this->sede_id)->orWhere('id_unidad_organica', '1'))->pluck('nombre', 'id_unidad_organica'))
                        ->searchable()
                        ->live() // Añade esto para que Livewire rastree mejor el cambio
                        ->placeholder('Seleccione un área'),
                    Select::make('sede_id')
                        ->label('Filtrar por Sede')
                        ->options(\App\Models\Sede::all()->pluck('nombre', 'id_sede'))
                        ->searchable()
                        ->live() // Añade esto para que Livewire rastree mejor el cambio
                        // --- BLOQUEO PARA SUPERVISOR ---
                        // ->disabled(fn() => auth()->user()->hasRole('Control Interno - Supervisor'))
                        // ->dehydrated() // Asegura que el valor se envíe aunque esté deshabilitado
                        // -------------------------------
                        ->placeholder('Seleccione una sede'),
                    // NUEVA ACCIÓN DE EXPORTAR


                ])
                ->action(function (array $data) {
                    $this->desde = $data['desde'];
                    $this->hasta = $data['hasta'];
                    $this->area_id = $data['area_id'];
                    $this->sede_id = $data['sede_id'];

                    // Esto refresca los widgets automáticamente
                    // Dentro del action() en DashboardVisitas.php
                    $this->dispatch('updateDashboardCharts', [
                        'desde' => $this->desde,
                        'hasta' => $this->hasta,
                        'area' => $this->area_id, // Cambiado a 'area' para que coincida con el widget
                        'sede' => $this->sede_id,

                    ]);
                }),
            // ExportAction::make('exportarData')
            //     ->label('Exportar Excel')
            //     ->exporter(VisitaDashboardExporter::class)
            //     ->icon('heroicon-m-arrow-down-tray')
            //     ->color('success')
            //     // Aplicamos los mismos filtros que tienen los widgets
            //     ->modifyQueryUsing(
            //         fn(Builder $query) => $query
            //             ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            //             ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            //             ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            //             ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
            //     )
            //     ->columnMapping(true), // Para que no pida mapear columnas al usuario
            Action::make('imprimir')
                ->label('Imprimir Reporte')
                ->icon('heroicon-m-printer')
                ->color('gray')
                ->extraAttributes([
                    'onclick' => 'window.print(); return false;',
                ]),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Pasamos el estado actual de los filtros al widget
            VisitasStatsOverview::make([
                'desde' => $this->desde,
                'hasta' => $this->hasta,
                'area_id' => $this->area_id,
                'sede_id' => $this->sede_id,
            ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        // Hacemos lo mismo para todos los widgets del footer
        $filtros = [
            'desde' => $this->desde,
            'hasta' => $this->hasta,
            'area_id' => $this->area_id,
            'sede_id' => $this->sede_id,
        ];
        return [
            WidgetsVisitasPorAreaChart::make($filtros),
            FlujoHorarioChart::make($filtros),
            VisitasEstadoChart::make($filtros),
            VisitantesTipoChart::make($filtros),
            UltimasVisitasTable::make($filtros),
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
