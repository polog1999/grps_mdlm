<?php

namespace App\Filament\Clusters\RRHH\Resources\InformeActividads\Pages;

use App\Filament\Clusters\RRHH\Resources\InformeActividads\Actions\SubirInforme;
use App\Filament\Clusters\RRHH\Resources\InformeActividads\InformeActividadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInformeActividads extends ListRecords
{
    protected static string $resource = InformeActividadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SubirInforme::make(),
        ];
    }
}
