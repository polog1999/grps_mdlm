<?php

namespace App\Filament\Clusters\Visitas\Resources\Oficinas\Pages;

use App\Filament\Clusters\Visitas\Resources\Oficinas\OficinaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOficina extends EditRecord
{
    protected static string $resource = OficinaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
