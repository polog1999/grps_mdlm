<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\AuditoriaAreaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaArea extends EditRecord
{
    protected static string $resource = AuditoriaAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
