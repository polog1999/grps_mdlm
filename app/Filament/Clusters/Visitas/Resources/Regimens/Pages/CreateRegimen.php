<?php

namespace App\Filament\Clusters\Visitas\Resources\Regimens\Pages;

use App\Filament\Clusters\Visitas\Resources\Regimens\RegimenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegimen extends CreateRecord
{
    protected static string $resource = RegimenResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id_creo'] = auth()->id();
        $data['user_id_modi'] = auth()->id();

        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id_modi'] = auth()->id();

        return $data;
    }
}
