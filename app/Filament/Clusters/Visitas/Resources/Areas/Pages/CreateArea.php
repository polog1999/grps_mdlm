<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Pages;

use App\Filament\Clusters\Visitas\Resources\Areas\AreaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArea extends CreateRecord
{
    protected static string $resource = AreaResource::class;
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
}
