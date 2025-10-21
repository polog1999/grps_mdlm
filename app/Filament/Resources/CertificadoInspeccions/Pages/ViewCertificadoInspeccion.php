<?php

namespace App\Filament\Resources\CertificadoInspeccions\Pages;

use App\Filament\Resources\CertificadoInspeccions\CertificadoInspeccionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificadoInspeccion extends ViewRecord
{
    protected static string $resource = CertificadoInspeccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
