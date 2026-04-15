<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Pages;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use App\Filament\Clusters\Visitas\Resources\Visitas\Widgets\VisitasStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListVisitas extends ListRecords
{
    use ExposesTableToWidgets; // Esto "abre" la tabla para el widget
    protected static string $resource = VisitaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Registrar Visita')
        ];
    }
    
// Añade esto para refrescar cada 30 segundos
public function getHeaderWidgetsColumns(): int | array { return 1; }

protected function getHeaderWidgets(): array
{
    return [
        VisitasStatsOverview::class,
    ];
}


}



