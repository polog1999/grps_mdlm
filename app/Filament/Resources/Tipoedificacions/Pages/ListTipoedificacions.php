<?php

namespace App\Filament\Resources\Tipoedificacions\Pages;

use App\Filament\Resources\Tipoedificacions\TipoedificacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoedificacions extends ListRecords
{
    protected static string $resource = TipoedificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
