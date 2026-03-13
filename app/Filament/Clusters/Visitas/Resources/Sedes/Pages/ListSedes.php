<?php

namespace App\Filament\Clusters\Visitas\Resources\Sedes\Pages;

use App\Filament\Clusters\Visitas\Resources\Sedes\SedeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSedes extends ListRecords
{
    protected static string $resource = SedeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
