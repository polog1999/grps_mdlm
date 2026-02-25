<?php

namespace App\Filament\Clusters\Visitas\Resources\Clasificacions\Pages;

use App\Filament\Clusters\Visitas\Resources\Clasificacions\ClasificacionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClasificacion extends CreateRecord
{
    protected static string $resource = ClasificacionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id_creo'] = auth()->id();
        $data['user_id_modi'] = auth()->id();

        return $data;
    }
}
