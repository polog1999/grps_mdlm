<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\AuditoriaRegimenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaRegimen extends EditRecord
{
    protected static string $resource = AuditoriaRegimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
