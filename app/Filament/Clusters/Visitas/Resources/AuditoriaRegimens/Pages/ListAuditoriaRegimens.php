<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\AuditoriaRegimenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaRegimens extends ListRecords
{
    protected static string $resource = AuditoriaRegimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
