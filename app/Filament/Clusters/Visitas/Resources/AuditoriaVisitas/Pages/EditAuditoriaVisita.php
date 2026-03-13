<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\AuditoriaVisitaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaVisita extends EditRecord
{
    protected static string $resource = AuditoriaVisitaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
