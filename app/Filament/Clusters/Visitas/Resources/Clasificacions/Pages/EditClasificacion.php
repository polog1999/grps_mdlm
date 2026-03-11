<?php

namespace App\Filament\Clusters\Visitas\Resources\Clasificacions\Pages;

use App\Filament\Clusters\Visitas\Resources\Clasificacions\ClasificacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClasificacion extends EditRecord
{
    protected static string $resource = ClasificacionResource::class;

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
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
