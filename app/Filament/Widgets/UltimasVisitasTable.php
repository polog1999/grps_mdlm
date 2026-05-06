<?php

namespace App\Filament\Widgets;

use App\Models\VisitaHistorico;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;

class UltimasVisitasTable extends BaseWidget
{
    // Cambiado a protected static para asegurar que Filament lo tome
    protected static ?string $pollingInterval = '5s';
    // Esto asegura que cada vez que Livewire haga polling, se limpie el estado de la tabla
    // protected $listeners = [
    //     '$refresh' => '$refresh',
    // ];
    protected static ?int $sort = 3;
    protected int | array | string $columnSpan = 'full';

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

        // IMPORTANTE: Resetear la tabla para que ejecute el query de nuevo
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Últimas 5 Visitas Registradas')
            ->headerActions([
                // BOTÓN DE EXPORTAR ESTÉTICO NATIVO DE TABLAS
                Action::make('exportarExcel')
                    ->label('Exportar Excel')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->size('sm')
                    ->action('exportar'),
            ])

            ->query(
                VisitaHistorico::query()
                ->where('origen', 'SISTEMA')
                    ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
                    ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
                    ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
                    ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
                    ->orderBy('fecha', 'desc')
                    ->orderBy('hora_ingreso', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nombres_completos')
                    ->label('Visitante'),

                Tables\Columns\TextColumn::make('sede.nombre')
                    ->label('Sede'),
                Tables\Columns\TextColumn::make('area')
                    ->label('Área destino')
                    ->wrap() // Esto es vital para que no se estire a lo ancho
                    ->extraAttributes([
                        'style' => 'font-size: 0.85rem;', // Un poco más pequeño para que entre todo
                    ]),

                Tables\Columns\TextColumn::make('hora_ingreso')
                    ->label('Ingreso')
                    ->time('H:i A'),

                Tables\Columns\IconColumn::make('hora_salida')
                    ->label('¿Salió?')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->hora_salida !== null),
            ])
            ->paginated(false);;
    }
    /**
     * Lógica de Exportación a Excel
     */
    public function exportar()
    {
        // Importante: En la exportación quitamos el limit(5) para que bajen TODOS los registros filtrados
        $data = VisitaHistorico::query()
        ->where('origen', 'SISTEMA')
            ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
            ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
            ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
            ->when($this->sede_id, fn($q) => $q->where('sede_id', $this->sede_id))
            ->orderBy('fecha', 'desc')
            ->get();

        return Excel::download(new class($data) implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping {
            private $items;
            public function __construct($items)
            {
                $this->items = $items;
            }
            public function collection()
            {
                return $this->items;
            }

            public function headings(): array
            {
                return ['Fecha', 'Visitante', 'Área Destino', 'Ingreso', 'Salida', 'Motivo'];
            }

            public function map($record): array
            {
                return [
                    $record->fecha,
                    $record->nombres_completos,
                    $record->area,
                    $record->hora_ingreso,
                    $record->hora_salida ?? 'En Sede',
                    $record->motivo,
                ];
            }
        }, 'listado_visitas_' . now()->format('Ymd_His') . '.xlsx');
    }

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Administrador OTIE', 'Control Interno - Supervisor']);
    }
}
