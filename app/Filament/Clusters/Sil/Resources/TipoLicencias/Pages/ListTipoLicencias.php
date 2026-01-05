<?php

namespace App\Filament\Clusters\Sil\Resources\TipoLicencias\Pages;

use App\Filament\Clusters\Sil\Resources\TipoLicencias\TipoLicenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoLicencias extends ListRecords
{
    protected static string $resource = TipoLicenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
