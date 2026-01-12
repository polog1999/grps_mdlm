<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Pages;

use App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\LicenciasLevantamientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLicenciasLevantamientos extends ListRecords
{
    protected static string $resource = LicenciasLevantamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
