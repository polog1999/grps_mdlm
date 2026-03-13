<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\AuditoriaTipoDocumentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaTipoDocumentos extends ListRecords
{
    protected static string $resource = AuditoriaTipoDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
