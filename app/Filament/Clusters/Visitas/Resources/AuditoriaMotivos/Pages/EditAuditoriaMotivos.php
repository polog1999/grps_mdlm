<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\AuditoriaMotivosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaMotivos extends EditRecord
{
    protected static string $resource = AuditoriaMotivosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
