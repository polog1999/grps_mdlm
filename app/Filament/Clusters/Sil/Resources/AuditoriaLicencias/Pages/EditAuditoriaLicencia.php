<?php

namespace App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Pages;

use App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\AuditoriaLicenciaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaLicencia extends EditRecord
{
    protected static string $resource = AuditoriaLicenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
