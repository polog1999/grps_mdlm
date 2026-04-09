<?php

namespace App\Filament\Clusters\Visitas\Resources\Regimens\Pages;

use App\Filament\Clusters\Visitas\Resources\Regimens\RegimenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegimens extends ListRecords
{
    protected static string $resource = RegimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
