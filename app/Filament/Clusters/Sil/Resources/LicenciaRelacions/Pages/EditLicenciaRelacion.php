<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Pages;

use App\Filament\Clusters\Sil\Resources\LicenciaRelacions\LicenciaRelacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLicenciaRelacion extends EditRecord
{
    protected static string $resource = LicenciaRelacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
