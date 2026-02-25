<?php

namespace App\Filament\Clusters\Visitas\Resources\Cargos\Pages;

use App\Filament\Clusters\Visitas\Resources\Cargos\CargoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCargo extends CreateRecord
{
    protected static string $resource = CargoResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id_creo'] = auth()->id();
        $data['user_id_modi'] = auth()->id();

        return $data;
    }
}
