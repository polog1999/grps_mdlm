<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\AuditoriaTipoDocumentoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaTipoDocumento extends EditRecord
{
    protected static string $resource = AuditoriaTipoDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
