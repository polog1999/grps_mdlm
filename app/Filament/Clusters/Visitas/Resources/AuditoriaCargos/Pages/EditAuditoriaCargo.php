<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\AuditoriaCargoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaCargo extends EditRecord
{
    protected static string $resource = AuditoriaCargoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
