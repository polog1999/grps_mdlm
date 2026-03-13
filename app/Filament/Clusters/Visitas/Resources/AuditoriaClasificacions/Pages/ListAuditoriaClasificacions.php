<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\AuditoriaClasificacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaClasificacions extends ListRecords
{
    protected static string $resource = AuditoriaClasificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
