<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Pages;

use App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\LicenciasLevantamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLicenciasLevantamiento extends EditRecord
{
    protected static string $resource = LicenciasLevantamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
