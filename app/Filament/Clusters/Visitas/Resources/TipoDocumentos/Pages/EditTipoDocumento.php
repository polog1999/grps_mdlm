<?php

namespace App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Pages;

use App\Filament\Clusters\Visitas\Resources\TipoDocumentos\TipoDocumentoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoDocumento extends EditRecord
{
    protected static string $resource = TipoDocumentoResource::class;

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
