<?php

namespace App\Filament\Clusters\Visitas\Resources\Sedes\Pages;

use App\Filament\Clusters\Visitas\Resources\Sedes\SedeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSede extends CreateRecord
{
    protected static string $resource = SedeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['user_id_creo'] = auth()->id();
    $data['user_id_modi'] = auth()->id();

    return $data;
}
protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
