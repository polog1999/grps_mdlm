<?php

namespace App\Filament\Widgets;

use App\Models\VisitaHistorico;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;

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

    #[On('updateDashboardCharts')]
    public function updateFilters(array $data): void
    {
        $this->desde = $data['desde'] ?? null;
        $this->hasta = $data['hasta'] ?? null;
        $this->area_id = $data['area'] ?? null;

        // IMPORTANTE: Resetear la tabla para que ejecute el query de nuevo
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                VisitaHistorico::query()
                    ->when($this->desde, fn($q) => $q->whereDate('fecha', '>=', $this->desde))
                    ->when($this->hasta, fn($q) => $q->whereDate('fecha', '<=', $this->hasta))
                    ->when($this->area_id, fn($q) => $q->where('area_id', $this->area_id))
                    ->orderBy('fecha', 'desc')
                    ->orderBy('hora_ingreso', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nombres_completos')
                    ->label('Visitante'),

                Tables\Columns\TextColumn::make('area')
                    ->label('Área destino'),

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
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Administrador OTIE', 'Control Interno - Supervisor']);
    }
}
