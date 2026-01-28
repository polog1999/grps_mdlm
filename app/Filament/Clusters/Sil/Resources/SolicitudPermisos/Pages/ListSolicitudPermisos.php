<?php

namespace App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Pages;

use App\Filament\Clusters\Sil\Resources\SolicitudPermisos\SolicitudPermisoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSolicitudPermisos extends ListRecords
{
    protected static string $resource = SolicitudPermisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
