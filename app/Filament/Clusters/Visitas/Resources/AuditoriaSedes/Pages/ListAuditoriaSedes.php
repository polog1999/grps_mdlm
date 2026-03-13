<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\AuditoriaSedeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaSedes extends ListRecords
{
    protected static string $resource = AuditoriaSedeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
