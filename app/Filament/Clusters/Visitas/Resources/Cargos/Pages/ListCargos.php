<?php

namespace App\Filament\Clusters\Visitas\Resources\Cargos\Pages;

use App\Filament\Clusters\Visitas\Resources\Cargos\CargoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCargos extends ListRecords
{
    protected static string $resource = CargoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
