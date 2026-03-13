<?php

namespace App\Filament\Clusters\Visitas\Resources\Clasificacions\Pages;

use App\Filament\Clusters\Visitas\Resources\Clasificacions\ClasificacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClasificacions extends ListRecords
{
    protected static string $resource = ClasificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
