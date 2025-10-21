<?php

namespace App\Filament\Resources\Tipoedificacions\Pages;

use App\Filament\Resources\Tipoedificacions\TipoedificacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoedificacion extends EditRecord
{
    protected static string $resource = TipoedificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
