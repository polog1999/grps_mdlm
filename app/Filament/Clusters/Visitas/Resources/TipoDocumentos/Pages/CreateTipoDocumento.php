<?php

namespace App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Pages;

use App\Filament\Clusters\Visitas\Resources\TipoDocumentos\TipoDocumentoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTipoDocumento extends CreateRecord
{
    protected static string $resource = TipoDocumentoResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id_creo'] = auth()->id();
        $data['user_id_modi'] = auth()->id();

        return $data;
    }
}
