<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\AuditoriaSedeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaSede extends EditRecord
{
    protected static string $resource = AuditoriaSedeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
