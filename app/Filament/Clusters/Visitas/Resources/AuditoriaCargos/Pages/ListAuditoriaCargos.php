<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\AuditoriaCargoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaCargos extends ListRecords
{
    protected static string $resource = AuditoriaCargoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
