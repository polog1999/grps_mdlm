<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Pages;

use App\Filament\Clusters\Sil\Resources\LicenciaRelacions\LicenciaRelacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLicenciaRelacions extends ListRecords
{
    protected static string $resource = LicenciaRelacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
