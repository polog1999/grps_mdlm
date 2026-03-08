<?php

namespace App\Filament\Clusters\RRHH\Resources\Asistencias\Pages;

use App\Filament\Clusters\RRHH\Resources\Asistencias\AsistenciaResource;
use App\Filament\Clusters\RRHH\Resources\Asistencias\Actions\RegistrarAlmuerzo;
use App\Filament\Clusters\RRHH\Resources\Asistencias\Actions\RegistrarEntrada;
use App\Filament\Clusters\RRHH\Resources\Asistencias\Actions\RegistrarSalida;
use Filament\Resources\Pages\ListRecords;

class ListAsistencias extends ListRecords
{
    protected static string $resource = AsistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RegistrarEntrada::make(),
            RegistrarAlmuerzo::make(),
            RegistrarSalida::make(),
        ];
    }
}

