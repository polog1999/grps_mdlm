<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\AuditoriaAreaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaAreas extends ListRecords
{
    protected static string $resource = AuditoriaAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
