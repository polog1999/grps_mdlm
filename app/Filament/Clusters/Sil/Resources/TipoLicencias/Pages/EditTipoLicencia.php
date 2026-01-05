<?php

namespace App\Filament\Clusters\Sil\Resources\TipoLicencias\Pages;

use App\Filament\Clusters\Sil\Resources\TipoLicencias\TipoLicenciaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoLicencia extends EditRecord
{
    protected static string $resource = TipoLicenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
