<?php

namespace App\Filament\Clusters\Visitas\Resources\Motivos\Pages;

use App\Filament\Clusters\Visitas\Resources\Motivos\MotivoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMotivo extends CreateRecord
{
    protected static string $resource = MotivoResource::class;
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
