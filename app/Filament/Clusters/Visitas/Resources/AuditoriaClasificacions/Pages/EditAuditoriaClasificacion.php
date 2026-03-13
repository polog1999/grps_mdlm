<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\AuditoriaClasificacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaClasificacion extends EditRecord
{
    protected static string $resource = AuditoriaClasificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
