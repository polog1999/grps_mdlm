<?php

namespace App\Filament\Clusters\Visitas\Resources\Sedes\Pages;

use App\Filament\Clusters\Visitas\Resources\Sedes\SedeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSede extends EditRecord
{
    protected static string $resource = SedeResource::class;

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
