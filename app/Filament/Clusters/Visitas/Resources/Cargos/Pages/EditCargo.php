<?php

namespace App\Filament\Clusters\Visitas\Resources\Cargos\Pages;

use App\Filament\Clusters\Visitas\Resources\Cargos\CargoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCargo extends EditRecord
{
    protected static string $resource = CargoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id_modi'] = auth()->id();

        return $data;
    }
}
