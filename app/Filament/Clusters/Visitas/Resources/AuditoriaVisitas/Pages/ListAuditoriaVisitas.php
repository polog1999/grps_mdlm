<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\AuditoriaVisitaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaVisitas extends ListRecords
{
    protected static string $resource = AuditoriaVisitaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
