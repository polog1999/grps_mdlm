<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\AuditoriaMotivosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaMotivos extends ListRecords
{
    protected static string $resource = AuditoriaMotivosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
